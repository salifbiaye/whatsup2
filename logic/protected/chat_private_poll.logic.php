<?php
session_start();
if (!isset($_SESSION['email_id'])) {
    http_response_code(403);
    exit('Non autorisé');
}
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
header('Content-Type: text/html; charset=utf-8');
if (!$chat || !isset($chat->messages->message)) {
    echo '<div class="text-gray-400 text-center">Aucun message.</div>';
    exit();
}
foreach ($chat->messages->message as $msg) {
    $is_me = ((string)$msg['sender'] === $email_id);
    echo '<div class="mb-3 ' . ($is_me ? 'text-right' : 'text-left') . '">';
    echo '<div class="inline-block ' . ($is_me ? 'bg-amber-100' : 'bg-gray-200') . ' px-4 py-2 rounded shadow">';
    echo '<div class="text-sm text-gray-800">' . nl2br(htmlspecialchars($msg->text)) . '</div>';
    if (isset($msg->file)) {
        echo '<a class="block mt-1 text-xs text-amber-600 hover:underline" href="/whatsup/' . htmlspecialchars($msg->file['path']) . '" target="_blank">📎 ' . htmlspecialchars($msg->file['name']) . '</a>';
    }
    echo '</div>';
    echo '<div class="text-xs text-gray-400 mt-1">';
    if ($is_me) {
        echo 'Moi';
    } else {
        $sender_name = '';
        foreach ($users->user as $u) {
            if ((string)$u['id'] === (string)$msg['sender']) {
                $sender_name = htmlspecialchars($u->displayName);
                break;
            }
        }
        echo $sender_name ? $sender_name : htmlspecialchars($msg['sender']);
    }
    echo ' | ' . htmlspecialchars(date('d/m/Y H:i', strtotime($msg['timestamp'])));
    echo '</div>';
    echo '</div>';
}
