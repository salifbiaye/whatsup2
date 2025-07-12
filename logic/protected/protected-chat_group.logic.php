<?php
session_start();
require_once __DIR__ . '/../../utils.php';
$userId = $_SESSION['email_id'] ?? null;
$group_id = $_GET['group'] ?? '';
$group = null;
$messages = [];
$members = [];
if (!$userId) {
    header('Location: /whatsup2/login');
    exit();
}
if (!$group_id) {
    $content = '';
    include __DIR__ . '/../../template/protected/chat_group.template.php';
    include __DIR__ . '/sidebar.logic.php';
    ob_start();
    include __DIR__ . '/../../template/protected/protected.layout.php';
    echo ob_get_clean();
    exit();
}
$groups_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/groups.xml');
$users_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
foreach ($groups_xml->group as $g) {
    if ((string)$g['id'] === $group_id) {
        $group = [
            'id' => (string)$g['id'],
            'name' => (string)$g->name,
        ];
        $members = array_map('strval', (array)$g->members->member);
        break;
    }
}
if (!$group || !in_array($userId, $members)) {
    $content = '<div class="text-center text-red-500 mt-16">Groupe introuvable ou accès refusé.</div>';
    include __DIR__ . '/sidebar.logic.php';
    include __DIR__ . '/../../template/protected/protected.layout.php';
    exit();
}
// Gestion envoi message groupe
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ((isset($_POST['message']) && trim($_POST['message']) !== '') || (isset($_FILES['file']) && $_FILES['file']['tmp_name'])) &&
    isset($_POST['send_group_message']) && isset($_POST['group_id']) && $_POST['group_id'] === $group_id
) {
    $text = isset($_POST['message']) ? trim($_POST['message']) : '';
    $msg_id = uniqid('gmsg');
    // Ajout UNIQUEMENT dans groups.xml
    $groups_path = __DIR__ . '/../../storage/xml/groups.xml';
    $groups_xml = simplexml_load_file($groups_path);
    foreach ($groups_xml->group as $g) {
        if ((string)$g['id'] === $group_id) {
            if (!isset($g->messages)) {
                $g->addChild('messages');
            }
            $msg2 = $g->messages->addChild('message');
            $msg2->addAttribute('id', $msg_id);
            $msg2->addAttribute('sender', $userId);
            $msg2->addAttribute('timestamp', date('c'));
            $msg2->addChild('text', $text);
            // Gestion fichier joint (optionnel)
            if (isset($_FILES['file']) && $_FILES['file']['tmp_name']) {
                $filename = basename($_FILES['file']['name']);
                $filepath = 'storage/files/' . uniqid() . '_' . $filename;
                if (!is_dir(__DIR__ . '/../../storage/files')) {
                    mkdir(__DIR__ . '/../../storage/files', 0777, true);
                }
                move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . '/../../' . $filepath);
                $fileNode2 = $msg2->addChild('file');
                $fileNode2->addAttribute('name', $filename);
                $fileNode2->addAttribute('type', $_FILES['file']['type']);
                $fileNode2->addAttribute('path', $filepath);
            }
            break;
        }
    }
    $groups_xml->asXML($groups_path);
    header('Location: /whatsup2/chat_group?group=' . $group_id);
    exit();
}
// Charger les messages du groupe (fichier XML: storage/xml/groups.xml)
$groups_path = __DIR__ . '/../../storage/xml/groups.xml';
$groups_xml = simplexml_load_file($groups_path);
foreach ($groups_xml->group as $g) {
    if ((string)$g['id'] === $group_id) {
        if (isset($g->messages)) {
            foreach ($g->messages->message as $msg) {
                $senderId = (string)$msg['sender'];
                $senderName = '';
                foreach ($users_xml->user as $u) {
                    if ((string)$u['id'] === $senderId) {
                        $senderName = (string)$u->displayName;
                        break;
                    }
                }
                $messages[] = [
                    'id' => (string)$msg['id'],
                    'sender' => $senderId,
                    'senderName' => $senderName,
                    'timestamp' => (string)$msg['timestamp'],
                    'text' => (string)$msg->text,
                    'file' => isset($msg->file) ? [
                        'name' => (string)$msg->file['name'],
                        'type' => (string)$msg->file['type'],
                        'path' => (string)$msg->file['path'],
                    ] : null,
                ];
            }
        }
        break;
    }
}

$content = '';
include __DIR__ . '/../../template/protected/chat_group.template.php';
include __DIR__ . '/sidebar.logic.php';
ob_start();
include __DIR__ . '/../../template/protected/protected.layout.php';
echo ob_get_clean();
