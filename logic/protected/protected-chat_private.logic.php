<?php
/**
 * Private Chat Logic - WhattsUp (VERSION CORRIGÉE)
 * Toutes les fonctions métier pour la gestion des chats privés et contacts.
 */
session_start();
require_once __DIR__ . '/../../utils.php';

// ========== HELPERS & MÉTIER ========== //

/**
 * Valide l'entrée pour l'ajout de contact.
 */
function validate_contact_input($contact_query) {
    if ($contact_query === '') {
        return [false, "<div class='text-red-500 mb-2'>Champ obligatoire.</div>"];
    }
    return [true, ''];
}

/**
 * Cherche un utilisateur par id/email/displayName.
 */
function find_user_by_query($users_xml, $query) {
    foreach ($users_xml->user as $u) {
        if ((string)$u['id'] === $query || (string)$u->email === $query || (string)$u->displayName === $query) {
            return $u;
        }
    }
    return null;
}

/**
 * Ajoute un contact à la liste de l'utilisateur.
 */
function add_contact($users_xml, $current_user_id, $contact_id) {
    $me = null;
    foreach ($users_xml->user as $u) {
        if ((string)$u['id'] === $current_user_id) {
            $me = $u;
            break;
        }
    }
    if (!$me) return [false, "<div class='text-red-500 mb-2'>Erreur interne utilisateur.</div>"];
    if (!isset($me->contacts)) $me->addChild('contacts');
    foreach ($me->contacts->contact as $c) {
        if ((string)$c == $contact_id) {
            return [false, "<div class='text-red-500 mb-2'>Ce contact est déjà dans votre liste.</div>"];
        }
    }
    if ($contact_id == $current_user_id) {
        return [false, "<div class='text-red-500 mb-2'>Vous ne pouvez pas vous ajouter vous-même.</div>"];
    }
    $me->contacts->addChild('contact', $contact_id);
    $users_xml->asXML(__DIR__ . '/../../storage/xml/users.xml');
    return [true, ''];
}

/**
 * Récupère un contact par son ID.
 */
function get_contact_by_id($users_xml, $contact_id) {
    foreach ($users_xml->user as $u) {
        if ((string)$u['id'] === $contact_id) return $u;
    }
    return null;
}

/**
 * Trouve ou crée un chat privé entre deux users.
 */
function find_or_create_private_chat($private_chats, $userId, $contact_id) {
    foreach ($private_chats->chat as $c) {
        if ((($c['user1'] == $userId && $c['user2'] == $contact_id) || ($c['user1'] == $contact_id && $c['user2'] == $userId))) {
            return $c;
        }
    }
    $chat_id = 'c' . (count($private_chats->chat) + 1);
    $chat = $private_chats->addChild('chat');
    $chat->addAttribute('id', $chat_id);
    $chat->addAttribute('user1', $userId);
    $chat->addAttribute('user2', $contact_id);
    $chat->addChild('messages');
    $private_chats->asXML(__DIR__ . '/../../storage/xml/private_chats.xml');
    return $chat;
}

/**
 * Ajoute un message privé (texte + fichier optionnel) - VERSION CORRIGÉE
 */
function send_private_message($private_chats, $chat, $userId, $contact_id) {
    // Vérifier que le chat a une section messages
    if (!isset($chat->messages)) {
        $chat->addChild('messages');
    }

    $text = trim($_POST['text'] ?? '');
    // Chiffrer le message avant de l'enregistrer
    $encryptedText = encryptMessage($text);
    
    $msg = $chat->messages->addChild('message');
    $msg->addAttribute('id', 'm' . (count($chat->messages->message) + 1));
    $msg->addAttribute('sender', $userId);
    $msg->addAttribute('timestamp', date('c'));
    $msg->addAttribute('encrypted', 'true'); // Marquer comme chiffré
    $msg->addChild('text', $encryptedText);

    // Gestion du fichier joint (si présent)
    if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['file']['name']);
        $filename_clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        $filepath = 'storage/files/' . uniqid() . '_' . $filename_clean;
        $storage_path = __DIR__ . '/../../storage/files';
        
        // Créer le dossier si nécessaire
        if (!is_dir($storage_path)) {
            mkdir($storage_path, 0777, true);
        }
        
        $full_path = $storage_path . '/' . basename($filepath);
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {
            $file = $msg->addChild('file');
            $file->addAttribute('name', $filename);
            $file->addAttribute('type', $_FILES['file']['type']);
            $file->addAttribute('path', $filepath);
        } else {
            error_log("Erreur lors de l'upload du fichier : " . $_FILES['file']['name']);
            return [false, "Erreur lors de l'upload du fichier."];
        }
    }

    // CORRECTION PRINCIPALE : Sauvegarder le XML complet
    $xml_path = __DIR__ . '/../../storage/xml/private_chats.xml';
    
    // Vérifier les permissions d'écriture
    if (!is_writable($xml_path)) {
        error_log("Erreur : fichier private_chats.xml non accessible en écriture");
        return [false, "Erreur système : impossible d'écrire dans le fichier."];
    }

    // Sauvegarder le XML complet (pas juste $chat)
    if ($private_chats->asXML($xml_path)) {
        return [true, "Message envoyé avec succès."];
    } else {
        error_log("Erreur lors de la sauvegarde du XML");
        return [false, "Erreur lors de la sauvegarde."];
    }
}

/**
 * Récupère les messages d'un chat privé.
 */
function get_private_messages($chat) {
    $messages = [];
    $users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
    $user_map = [];
    
    foreach ($users->user as $u) {
        $user_map[(string)$u['id']] = isset($u->displayName) ? (string)$u->displayName : (string)$u['id'];
    }
    
    if (isset($chat->messages->message)) {
        foreach ($chat->messages->message as $msg) {
            $sender_id = (string)$msg['sender'];
            $messages[] = [
                'id' => (string)$msg['id'],
                'sender' => $sender_id,
                'senderName' => $user_map[$sender_id] ?? $sender_id,
                'timestamp' => (string)$msg['timestamp'],
                'text' => (isset($msg['encrypted']) && (string)$msg['encrypted'] === 'true') 
                    ? decryptMessage((string)$msg->text)
                    : (string)$msg->text,
                'file' => isset($msg->file) ? [
                    'name' => (string)$msg->file['name'],
                    'type' => (string)$msg->file['type'],
                    'path' => (string)$msg->file['path']
                ] : null
            ];
        }
    }
    return $messages;
}

/**
 * Ajoute une demande de contact
 */
function ajouter_demande_contact($current_user_id, $contact_id) {
    if ($contact_id === $current_user_id) return false;

    $demande_file = __DIR__ . '/../../storage/xml/demandes.xml';

    if (file_exists($demande_file)) {
        $demandes_xml = simplexml_load_file($demande_file);
    } else {
        $demandes_xml = new SimpleXMLElement('<demandes></demandes>');
    }

    // Vérifier si une demande similaire existe déjà
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

// ========== CONTRÔLEUR PRINCIPAL ========== //

$alert = '';
$current_user_id = $_SESSION['email_id'] ?? null;
$contact_id = $_GET['user'] ?? '';

if (!$current_user_id) {
    header('Location: /whatsup/login');
    exit();
}

// Charger les users
$users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$contact = null;
if ($contact_id) {
    $contact = get_contact_by_id($users, $contact_id);
    if (!$contact) {
        $alert = "<div class='text-red-500 mb-2'>Contact introuvable.</div>";
    }
}

// Gestion ajout de contact (depuis POST)
if (isset($_POST['create_contact'])) {
    $contact_query = trim($_POST['contact_query'] ?? '');
    list($valid, $msg) = validate_contact_input($contact_query);
    if ($valid) {
        $found = find_user_by_query($users, $contact_query);
        if ($found) {
            list($ok, $msg2) = add_contact($users, $current_user_id, (string)$found['id']);
            if ($ok) {
                // Notifier le contact par email
                if (isset($found->email) && filter_var((string)$found->email, FILTER_VALIDATE_EMAIL)) {
                    $to = (string)$found->email;
                    $subject = "Nouvelle demande de contact sur WhatsUp";
                    $body = "Bonjour,<br><br>Vous avez reçu une nouvelle demande d'ajout de contact  sur WhatsUp.";
                    send_gmail_notification($to, $subject, $body);
                }
                ajouter_demande_contact($current_user_id, (string)$found['id']);
                header('Location: /whatsup/demandes');
                exit();
            } else {
                $alert = $msg2;
            }
        } else {
            $alert = "<div class='text-red-500 mb-2'>Aucun utilisateur trouvé.</div>";
        }
    } else {
        $alert = $msg;
    }
}

// Charger les chats privés
$private_chats = simplexml_load_file(__DIR__ . '/../../storage/xml/private_chats.xml');
$chat = null;
if ($contact_id && $contact) {
    $chat = find_or_create_private_chat($private_chats, $current_user_id, $contact_id);
}

// CORRECTION : Gestion envoi de message privé
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['send_message']) &&
    $chat && $contact_id && $current_user_id
) {
    // Ordre correct des paramètres
    list($success, $message) = send_private_message($private_chats, $chat, $current_user_id, $contact_id);
    
    if ($success) {
        // Redirection après succès
        header('Location: /whatsup/chat_private?user=' . urlencode($contact_id));
        exit();
    } else {
        $alert = "<div class='text-red-500 mb-2'>$message</div>";
    }
}

// Préparation affichage
$messages = $chat ? get_private_messages($chat) : [];

// Préparation des données du contact
if ($contact && ($contact instanceof SimpleXMLElement)) {
    $contactAvatar = (isset($contact->avatar) && trim((string)$contact->avatar) !== '') ? (string)$contact->avatar : 'storage/avatars/avatar_default.png';
    if (isset($contact->displayName) && trim((string)$contact->displayName) !== '') {
        $contactDisplayName = (string)$contact->displayName;
    } elseif (isset($contact['displayName']) && trim((string)$contact['displayName']) !== '') {
        $contactDisplayName = (string)$contact['displayName'];
    } else {
        $contactDisplayName = 'Utilisateur';
    }
} else {
    $contactAvatar = 'storage/avatars/avatar_default.png';
    $contactDisplayName = 'Utilisateur';
}

function render_private_chat($contact, $messages, $alert, $contact_id, $chat, $current_user_id, $contactAvatar, $contactDisplayName) {
    ob_start();
    include __DIR__ . '/../../template/protected/chat_private.template.php';
    $content = ob_get_clean();
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
}

// Affichage final
render_private_chat(
    $contact, 
    $messages, 
    $alert, 
    $contact_id, 
    $chat, 
    $current_user_id,
    $contactAvatar ?? 'storage/avatars/avatar_default.png',
    $contactDisplayName ?? 'Utilisateur'
);
?>