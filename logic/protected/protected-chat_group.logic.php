<?php
/**
 * Groupe Chat Logic - WhattsUp
 * Gestion des groupes de chat avec fonctions métier séparées
 * Refactorisé pour lisibilité, testabilité et évolutivité
 */

session_start();
require_once __DIR__ . '/../../utils.php';

// ========== CONFIGURATION ========== //

define('GROUPS_XML_PATH', __DIR__ . '/../../storage/xml/groups.xml');
define('USERS_XML_PATH', __DIR__ . '/../../storage/xml/users.xml');
define('FILES_STORAGE_PATH', __DIR__ . '/../../storage/files');

// ========== FONCTIONS MÉTIER - VALIDATION ========== //

/**
 * Vérifie que les données de création de groupe sont valides
 */
function validate_group_creation_input($group_name, $members) {
    if (empty(trim($group_name))) {
        return [false, "<div class='text-red-500 mb-2'>Le nom du groupe est obligatoire.</div>"];
    }
    
    if (empty($members)) {
        return [false, "<div class='text-red-500 mb-2'>Au moins un membre doit être sélectionné.</div>"];
    }
    
    return [true, ''];
}

// ========== FONCTIONS MÉTIER - GESTION GROUPES ========== //

/**
 * Charge ou crée le fichier XML des groupes
 */
function load_groups_xml() {
    if (!file_exists(GROUPS_XML_PATH)) {
        $groups_xml = new SimpleXMLElement('<groups></groups>');
        $groups_xml->asXML(GROUPS_XML_PATH);
        return $groups_xml;
    }
    return simplexml_load_file(GROUPS_XML_PATH);
}

/**
 * Vérifie si un groupe avec ce nom existe déjà
 */
function group_name_exists($groups_xml, $group_name) {
    foreach ($groups_xml->group as $existing_group) {
        if (strtolower(trim((string)$existing_group->name)) === strtolower(trim($group_name))) {
            return true;
        }
    }
    return false;
}

/**
 * Génère un nouvel ID unique pour un groupe
 */
function generate_group_id($groups_xml) {
    return 'g' . (count($groups_xml->group) + 1);
}

/**
 * Crée un nouveau groupe
 */
function create_group($group_name, $members, $userId) {
    $groups_xml = load_groups_xml();
    
    // Vérification des doublons
    if (group_name_exists($groups_xml, $group_name)) {
        return [false, "<div class='text-red-500 mb-2'>Un groupe avec ce nom existe déjà.</div>"];
    }
    
    // Ajout du créateur dans les membres s'il n'y est pas déjà
    if (!in_array($userId, $members)) {
        $members[] = $userId;
    }
    
    // Création du groupe
    $group_id = generate_group_id($groups_xml);
    $group = $groups_xml->addChild('group');
    $group->addAttribute('id', $group_id);
    $group->addChild('name', htmlspecialchars($group_name));
    $group->addChild('admin', $userId);
    $group->addChild('created_at', date('c'));
    
    // Ajout des membres
    $members_xml = $group->addChild('members');
    foreach ($members as $member_id) {
        $members_xml->addChild('member', $member_id);
    }
    
    // Sauvegarde
    $groups_xml->asXML(GROUPS_XML_PATH);
    
    return [true, "<div class='text-green-600 mb-2'>Groupe créé avec succès.</div>"];
}

/**
 * Récupère un groupe par son ID
 */
function find_group_by_id($groups_xml, $group_id) {
    foreach ($groups_xml->group as $group) {
        if ((string)$group['id'] === $group_id) {
            return $group;
        }
    }
    return null;
}

/**
 * Retourne la liste des membres d'un groupe
 */
function get_group_members($group) {
    if (!isset($group->members)) {
        return [];
    }
    
    $members = [];
    foreach ($group->members->member as $member) {
        $members[] = (string)$member;
    }
    return $members;
}

/**
 * Vérifie si un utilisateur a accès à un groupe
 */
function can_user_access_group($group, $userId) {
    $members = get_group_members($group);
    return in_array($userId, $members);
}

// ========== FONCTIONS MÉTIER - MESSAGES ========== //

/**
 * Prépare le dossier de stockage des fichiers
 */
function ensure_files_directory() {
    if (!is_dir(FILES_STORAGE_PATH)) {
        mkdir(FILES_STORAGE_PATH, 0777, true);
    }
}

/**
 * Traite l'upload d'un fichier
 */
function process_file_upload($file) {
    if (!$file || !$file['tmp_name']) {
        return null;
    }
    
    ensure_files_directory();
    
    $filename = basename($file['name']);
    $safe_filename = uniqid() . '_' . $filename;
    $filepath = 'storage/files/' . $safe_filename;
    $full_path = __DIR__ . '/../../' . $filepath;
    
    if (move_uploaded_file($file['tmp_name'], $full_path)) {
        return [
            'name' => $filename,
            'type' => $file['type'],
            'path' => $filepath
        ];
    }
    
    return null;
}

/**
 * Ajoute un message dans un groupe
 */
function send_group_message($group, $userId, $text, $file = null) {
    // Initialisation du conteneur de messages si nécessaire
    if (!isset($group->messages)) {
        $group->addChild('messages');
    }
    
    // Création du message
    $msg_id = uniqid('gmsg');
    $msg = $group->messages->addChild('message');
    $msg->addAttribute('id', $msg_id);
    $msg->addAttribute('sender', $userId);
    $msg->addAttribute('timestamp', date('c'));
    $msg->addAttribute('encrypted', 'true'); // Marquer comme chiffré
    
    // Chiffrer le message avant de l'enregistrer
    $encryptedText = encryptMessage($text);
    $msg->addChild('text', $encryptedText);
    
    // Traitement du fichier attaché
    if ($file) {
        $file_info = process_file_upload($file);
        if ($file_info) {
            $fileNode = $msg->addChild('file');
            $fileNode->addAttribute('name', $file_info['name']);
            $fileNode->addAttribute('type', $file_info['type']);
            $fileNode->addAttribute('path', $file_info['path']);
        }
    }
    
    return $msg_id;
}

/**
 * Récupère les messages d'un groupe avec les informations des utilisateurs
 */
function get_group_messages($group, $users_xml) {
    $messages = [];
    
    if (!isset($group->messages)) {
        return $messages;
    }
    
    foreach ($group->messages->message as $msg) {
        $senderId = (string)$msg['sender'];
        $senderName = get_user_display_name($users_xml, $senderId);
        
        $message_data = [
            'id' => (string)$msg['id'],
            'sender' => $senderId,
            'senderName' => $senderName,
            'timestamp' => (string)$msg['timestamp'],
            'text' => (isset($msg['encrypted']) && (string)$msg['encrypted'] === 'true')
                ? decryptMessage((string)$msg->text)
                : (string)$msg->text,
            'file' => null
        ];
        
        // Ajout des informations de fichier si présent
        if (isset($msg->file)) {
            $message_data['file'] = [
                'name' => (string)$msg->file['name'],
                'type' => (string)$msg->file['type'],
                'path' => (string)$msg->file['path'],
            ];
        }
        
        $messages[] = $message_data;
    }
    
    return $messages;
}

/**
 * Récupère le nom d'affichage d'un utilisateur
 */
function get_user_display_name($users_xml, $userId) {
    $user = $users_xml->xpath('//user[@id="' . $userId . '"]');
    return $user ? (string)$user[0]->displayName : $userId;
}

/**
 * Récupère les informations de base d'un groupe pour l'affichage
 * Inclut le nom, le nombre de membres et les pseudos des membres (tronqués si nécessaire)
 */
function get_group_display_info($group_id) {
    // Charger les fichiers XML
    $groups_xml = load_groups_xml();
    $users_xml = simplexml_load_file(USERS_XML_PATH);
    
    // Trouver le groupe
    $group = find_group_by_id($groups_xml, $group_id);
    if (!$group) {
        return null;
    }
    
    // Récupérer les informations des membres
    $memberInfo = get_group_member_names($group, $users_xml);
    
    // Préparer les données pour le frontend
    return [
        'name' => (string)$group->name,
        'member_count' => count(get_group_members($group)),
        'members' => [
            'admin' => $memberInfo['admin']['name'],
            'others' => array_map(function($member) {
                return $member['name'];
            }, $memberInfo['other_members']),
            'others_count' => $memberInfo['others_count']
        ]
    ];
}

/**
 * Récupère la liste des pseudos des membres d'un groupe
 * Avec option de troncage si plus de 4 membres
 */
function get_group_member_names($group, $users_xml, $truncate = true) {
    $members = get_group_members($group);
    $memberNames = [];
    
    // Récupérer les pseudos pour tous les membres
    foreach ($members as $member) {
        $memberNames[] = [
            'id' => (string)$group,
            'name' => get_user_display_name($users_xml, (string)$member),
            'is_admin' => (string)$member === (string)$group->admin
        ];
    }
    
    // Trier les membres : admin en premier, puis les autres par ordre alphabétique
    usort($memberNames, function($a, $b) {
        if ($a['is_admin'] && !$b['is_admin']) return -1;
        if (!$a['is_admin'] && $b['is_admin']) return 1;
        return strcmp($a['name'], $b['name']);
    });
    
    // Tronquer la liste si demandé et plus de 4 membres
    if ($truncate && count($memberNames) > 4) {
        $othersCount = count($memberNames) - 4;
        return [
            'admin' => $memberNames[0],
            'other_members' => array_slice($memberNames, 1, 3),
            'others_count' => $othersCount
        ];
    }
    
    return [
        'admin' => $memberNames[0],
        'other_members' => array_slice($memberNames, 1),
        'others_count' => 0
    ];
}

// ========== FONCTIONS CONTRÔLEUR ========== //

/**
 * Redirige vers la page de login si non authentifié
 */
function redirect_if_not_authenticated($userId) {
    if (!$userId) {
        header('Location: /whatsup/login');
        exit();
    }
}

/**
 * Affiche la page vide (aucun groupe sélectionné)
 */
function render_empty_group_page() {
    $content = '';
    include __DIR__ . '/../../template/protected/chat_group.template.php';
    include __DIR__ . '/sidebar.logic.php';
    ob_start();
    include __DIR__ . '/../../template/protected/protected.layout.php';
    echo ob_get_clean();
    exit();
}

/**
 * Affiche la page d'erreur d'accès refusé
 */
function render_group_access_denied() {
    $content = '<div class="text-center text-red-500 mt-16">Groupe introuvable ou accès refusé.</div>';
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
    exit();
}

/**
 * Gère l'envoi d'un message POST
 */
function handle_group_message_post($group, $userId, $group_id, $groups_xml) {
    $is_post = $_SERVER['REQUEST_METHOD'] === 'POST';
    $has_message = isset($_POST['message']) && trim($_POST['message']) !== '';
    $has_file = isset($_FILES['file']) && $_FILES['file']['tmp_name'];
    $is_group_message = isset($_POST['send_group_message']);
    $correct_group = isset($_POST['group_id']) && $_POST['group_id'] === $group_id;
    
    if ($is_post && ($has_message || $has_file) && $is_group_message && $correct_group) {
        $text = isset($_POST['message']) ? trim($_POST['message']) : '';
        $file = $_FILES['file'] ?? null;
        
        send_group_message($group, $userId, $text, $file);
        $groups_xml->asXML(GROUPS_XML_PATH);
        
        header('Location: /whatsup/chat_group?group=' . $group_id);
        exit();
    }
}

/**
 * Affiche la page du groupe avec messages et membres
 */
function render_group_page($group, $users_xml, $group_id, $userId) {
    $members = get_group_members($group);
    $messages = get_group_messages($group, $users_xml);
    
    $content = '';
    ob_start();
    include __DIR__ . '/../../template/protected/chat_group.template.php';
    $content = ob_get_clean();
    
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
}

// ========== CONTRÔLEUR PRINCIPAL ========== //

// Initialisation des variables
$userId = $_SESSION['email_id'] ?? null;
$group_id = $_GET['group'] ?? '';
$alert = '';

// Gestion de la création de groupe
if (isset($_POST['create_group']) && $userId) {
    $group_name = trim($_POST['group_name'] ?? '');
    $members = $_POST['members'] ?? [];
    
    list($is_valid, $validation_message) = validate_group_creation_input($group_name, $members);
    
    if ($is_valid) {
        list($success, $alert) = create_group($group_name, $members, $userId);
    } else {
        $alert = $validation_message;
    }
}

// Vérifications d'accès
redirect_if_not_authenticated($userId);

if (!$group_id) {
    render_empty_group_page();
}

// Chargement des données
$groups_xml = load_groups_xml();
$users_xml = simplexml_load_file(USERS_XML_PATH);
$group = find_group_by_id($groups_xml, $group_id);

// Vérification d'accès au groupe
if (!$group || !can_user_access_group($group, $userId)) {
    render_group_access_denied();
}

// Traitement des messages
handle_group_message_post($group, $userId, $group_id, $groups_xml);

// Affichage de la page
render_group_page($group, $users_xml, $group_id, $userId);

?>