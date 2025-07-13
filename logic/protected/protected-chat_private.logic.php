<?php
session_start();
require_once __DIR__ . '/../../utils.php';

// Gestion ajout contact à ma liste
$alert = '';
if (isset($_POST['create_contact']) && isset($_SESSION['email_id'])) {
    $contact_query = trim($_POST['contact_query'] ?? '');
    if ($contact_query === '') {
        $alert = "<div class='text-red-500 mb-2'>Champ obligatoire.</div>";
    } else {
        $users_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
        $found_id = null;
        foreach ($users_xml->user as $u) {
            if ((string)$u['id'] === $contact_query || (string)$u->email === $contact_query || (string)$u->displayName === $contact_query) {
                $found_id = (string)$u['id'];
                break;
            }
        }
        if (!$found_id) {
            $alert = "<div class='text-red-500 mb-2'>Aucun utilisateur trouvé.</div>";
        } else {
            // Trouver mon user
            $me = null;
            foreach ($users_xml->user as $u) {
                if ((string)$u['id'] === $_SESSION['email_id']) {
                    $me = $u;
                    break;
                }
            }
            if (!$me) {
                $alert = "<div class='text-red-500 mb-2'>Erreur interne utilisateur.</div>";
            } else {
                if (!isset($me->contacts)) $me->addChild('contacts');
                $already = false;
                foreach ($me->contacts->contact as $c) {
                    if ((string)$c == $found_id) {
                        $already = true;
                        break;
                    }
                }
                if ($already) {
                    $alert = "<div class='text-red-500 mb-2'>Ce contact est déjà dans votre liste.</div>";
                } else if ($found_id == $_SESSION['email_id']) {
                    $alert = "<div class='text-red-500 mb-2'>Vous ne pouvez pas vous ajouter vous-même.</div>";
                } else {
                    $me->contacts->addChild('contact', $found_id);
                    $users_xml->asXML(__DIR__ . '/../../storage/xml/users.xml');
                    // AJOUTE ICI LA CREATION DE DEMANDE
                    
                    ajouter_demande_contact($_SESSION['email_id'], $found_id);
                    $alert = "<div class='text-green-600 mb-2'>Contact ajouté avec succès !</div>";
                    header('Location: /whatsup2/demandes');
                }
            }
        }
    }
}

// Gestion création groupe (pour l'utilisateur courant)
if (isset($_POST['create_group']) && isset($_SESSION['email_id'])) {
    $group_name = trim($_POST['group_name'] ?? '');
    $members = $_POST['members'] ?? [];
    $userId = $_SESSION['email_id'];
    if ($group_name === '' || empty($members)) {
        $alert = "<div class='text-red-500 mb-2'>Nom et membres obligatoires.</div>";
    } else {
        // Le créateur est toujours membre
        if (!in_array($userId, $members)) {
            $members[] = $userId;
        }
        $groups_path = __DIR__ . '/../../storage/xml/groups.xml';
        if (!file_exists($groups_path)) {
            $groups_xml = new SimpleXMLElement('<groups></groups>');
        } else {
            $groups_xml = simplexml_load_file($groups_path);
        }
        // Vérifie que l'utilisateur n'a pas déjà un groupe avec ce nom
        $already = false;
        foreach ($groups_xml->group as $g) {
            if ((string)$g->name === $group_name && in_array($userId, array_map('strval', (array)$g->members->member))) {
                $already = true;
                break;
            }
        }
        if ($already) {
            $alert = "<div class='text-red-500 mb-2'>Vous avez déjà un groupe avec ce nom.</div>";
        } else {
            $group_id = 'g' . (count($groups_xml->group) + 1);
            $group = $groups_xml->addChild('group');
            $group->addAttribute('id', $group_id);
            $group->addChild('name', $group_name);
            $members_xml = $group->addChild('members');
            foreach ($members as $mid) {
                $members_xml->addChild('member', $mid);
            }
            $groups_xml->asXML($groups_path);
            $alert = "<div class='text-green-600 mb-2'>Groupe créé avec succès.</div>";
        }
    }
}

if (isset($_SESSION['email_id'])) {
    set_user_status($_SESSION['email_id'], 'online');
}
if (!isset($_SESSION['email_id'])) {
    header('Location: /whatsup2/login');
    exit();
}
$userId = $_SESSION['email_id'];
$contact_id = $_GET['user'] ?? '';
if (!$contact_id) {
    // Préparer variables pour template même sans contact
    $contactDisplayName = 'Aucun contact spécifié';
    $contactAvatar = 'storage/avatars/avatar_default.png';
    $title = 'Chat privé - Aucun contact';
    $messages = [];
    ob_start();
    include __DIR__ . '/../../template/protected/chat_private.template.php';
    $content = ob_get_clean();
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
    exit();
}
$users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$private_chats = simplexml_load_file(__DIR__ . '/../../storage/xml/private_chats.xml');
// Vérifier que le contact existe
$contact = null;
foreach ($users->user as $u) {
    if ((string)$u['id'] === $contact_id) {
        $contact = $u;
        break;
    }
}
if (!$contact) {
    $error = 'Contact introuvable.';
    $content = '<div class="text-center text-red-500">Contact introuvable.</div>';
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
    exit();
}
// Trouver ou créer le chat entre les deux users
$chat = null;
foreach ($private_chats->chat as $c) {
    if ((($c['user1'] == $userId && $c['user2'] == $contact_id) || ($c['user1'] == $contact_id && $c['user2'] == $userId))) {
        $chat = $c;
        break;
    }
}
if (!$chat) {
    $chat_id = 'c' . (count($private_chats->chat) + 1);
    $chat = $private_chats->addChild('chat');
    $chat->addAttribute('id', $chat_id);
    $chat->addAttribute('user1', $userId);
    $chat->addAttribute('user2', $contact_id);
    $chat->addChild('messages');
    $private_chats->asXML(__DIR__ . '/../../storage/xml/private_chats.xml');
}
// Envoyer un message
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (
        (isset($_POST['text']) && trim($_POST['text']) !== '') ||
        (isset($_FILES['file']) && $_FILES['file']['tmp_name'])
    )
) {
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';
    $msg_id = 'm' . (count($chat->messages->message) + 1);
    $msg = $chat->messages->addChild('message');
    $msg->addAttribute('id', $msg_id);
    $msg->addAttribute('sender', $userId);
    $msg->addAttribute('timestamp', date('c'));
    $msg->addChild('text', $text);
    // Gestion d'un fichier joint (optionnel)
    if (isset($_FILES['file']) && $_FILES['file']['tmp_name']) {
        // Nettoyer le nom du fichier pour éviter les problèmes avec les caractères spéciaux
        $filename = basename($_FILES['file']['name']);
        $filename_clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        $filepath = 'storage/files/' . uniqid() . '_' . $filename_clean;

        // Créer le dossier s'il n'existe pas
        $storage_path = __DIR__ . '/../../storage/files';
        if (!is_dir($storage_path)) {
            mkdir($storage_path, 0777, true);
        }

        // Construire le chemin complet
        $full_path = $storage_path . '/' . basename($filepath);

        // Vérifier si le dossier existe
        if (!is_dir(dirname($full_path))) {
            mkdir(dirname($full_path), 0777, true);
        }

        // Déplacer le fichier
        if (move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {
            $file = $msg->addChild('file');
            $file->addAttribute('name', $filename);
            $file->addAttribute('type', $_FILES['file']['type']);
            $file->addAttribute('path', $filepath);
        } else {
            // Si l'upload échoue, on envoie une alerte
            $alert = '<div class="text-red-500 mb-2">Erreur lors de l\'upload du fichier.</div>';
            header('Location: /whatsup2/chat_private?user=' . $contact_id);
            exit();
        }
    }
    $private_chats->asXML(__DIR__ . '/../../storage/xml/private_chats.xml');
    header('Location: /whatsup2/chat_private?user=' . $contact_id);
    exit();
}
// Charger les messages pour affichage initial
$messages = [];
if (isset($chat->messages->message)) {
    foreach ($chat->messages->message as $msg) {
        $messages[] = [
            'id' => (string)$msg['id'],
            'sender' => (string)$msg['sender'],
            'timestamp' => (string)$msg['timestamp'],
            'text' => (string)$msg->text,
            'file' => isset($msg->file) ? [
                'name' => (string)$msg->file['name'],
                'type' => (string)$msg->file['type'],
                'path' => (string)$msg->file['path']
            ] : null
        ];
    }
}

function ajouter_demande_contact($current_user_id, $contact_id) {
    if ($contact_id === $current_user_id) return false; // Pas de demande à soi-même

    $demande_file = __DIR__ . '/../../storage/xml/demandes.xml';

    // Charger ou créer le fichier XML des demandes
    if (file_exists($demande_file)) {
        $demandes_xml = simplexml_load_file($demande_file);
    } else {
        $demandes_xml = new SimpleXMLElement('<demandes></demandes>');
    }

    // Vérifier si une demande similaire existe déjà (pas de doublon pending)
    foreach ($demandes_xml->demande as $demande) {
        if (
            (string)$demande->sender_id === $current_user_id &&
            (string)$demande->receiver_id === $contact_id &&
            (string)$demande->status === 'pending'
        ) {
            return false; // Déjà en attente
        }
    }

    // Ajout de la demande
    $new_demande = $demandes_xml->addChild('demande');
    $new_demande->addChild('sender_id', $current_user_id);
    $new_demande->addChild('receiver_id', $contact_id);
    $new_demande->addChild('status', 'pending');
    $new_demande->addChild('date', date('c'));
    $demandes_xml->asXML($demande_file);
    return true;
}
$contactDisplayName = htmlspecialchars((string)$contact->displayName);
$contactAvatar = (string)$contact->avatar ?: 'whatsup2/storage/avatars/avatar_default.png';
$title = 'Chat privé - ' . $contactDisplayName;
ob_start();
include __DIR__ . '/../../template/protected/chat_private.template.php';
$content = ob_get_clean();
include __DIR__ . '/sidebar.logic.php';
include __DIR__ . '/../../template/protected/protected.layout.php';
