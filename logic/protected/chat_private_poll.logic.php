<?php
session_start();
if (!isset($_SESSION['email_id'])) {
    http_response_code(403);
    exit('Non autorisé');
}
require_once __DIR__ . '/../../utils.php';

$email_id = $_SESSION['email_id'];
$contact_id = $_GET['user'] ?? '';

if (!$contact_id) {
    exit('Contact non spécifié');
}

$users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$private_chats = simplexml_load_file(__DIR__ . '/../../storage/xml/private_chats.xml');

// Trouver le contact
$contact = null;
foreach ($users->user as $u) {
    if ((string)$u['id'] === $contact_id) {
        $contact = $u;
        break;
    }
}

if (!$contact) {
    exit('Contact introuvable');
}

// Trouver le chat
$chat = null;
foreach ($private_chats->chat as $c) {
    if ((($c['user1'] == $email_id && $c['user2'] == $contact_id) || ($c['user1'] == $contact_id && $c['user2'] == $email_id))) {
        $chat = $c;
        break;
    }
}

header('Content-Type: application/json');

// Récupérer le dernier ID de message connu du client
$last_known_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
$new_messages = [];
$message_count = 0;
$has_new_messages = false;

if (!$chat || !isset($chat->messages->message)) {
    echo json_encode(['messages' => [], 'has_new' => false, 'last_id' => 0]);
    exit();
}

// Parcourir les messages et ne prendre que les nouveaux
foreach ($chat->messages->message as $msg) {
    $message_count++;
    
    // Si le message est déjà connu du client, on passe au suivant
    if ($message_count <= $last_known_id) {
        continue;
    }
    
    $file_info = null;
    if (isset($msg->file)) {
        $file_info = [
            'name' => (string)$msg->file['name'],
            'path' => (string)$msg->file['path']
        ];
    }
    
    $messageText = (string)$msg->text;
    if (isset($msg['encrypted']) && (string)$msg['encrypted'] === 'true') {
        $messageText = decryptMessage($messageText);
    }
    
    $new_messages[] = [
        'id' => $message_count,
        'sender' => (string)$msg['sender'],
        'text' => $messageText,
        'timestamp' => (string)$msg['timestamp'],
        'file' => $file_info,
        'is_own' => (string)$msg['sender'] === $email_id
    ];
    $has_new_messages = true;
}

echo json_encode([
    'messages' => $new_messages,
    'has_new' => $has_new_messages,
    'last_id' => $message_count,
    'total_count' => $message_count
]);