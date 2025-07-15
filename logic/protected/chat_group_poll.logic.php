<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../utils.php';

$userId = $_SESSION['email_id'] ?? null;
$group_id = $_GET['group'] ?? '';

if (!$userId || !$group_id) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing user or group ID'
    ]);
    exit();
}

$groups_path = __DIR__ . '/../../storage/xml/groups.xml';
$users_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$groups_xml = simplexml_load_file($groups_path);

// Trouver le groupe
$group = null;
foreach ($groups_xml->group as $g) {
    if ((string)$g['id'] === $group_id) {
        $group = $g;
        break;
    }
}

if (!$group) {
    echo json_encode([
        'success' => false,
        'error' => 'Group not found'
    ]);
    exit();
}

// Convertir les messages en array
$messages = [];
if (isset($group->messages)) {
    foreach ($group->messages->message as $msg) {
        $senderId = (string)$msg['sender'];
        $senderName = '';
        
        // Trouver le nom de l'expéditeur
        foreach ($users_xml->user as $u) {
            if ((string)$u['id'] === $senderId) {
                $senderName = (string)$u->displayName;
                break;
            }
        }
        
        $file_info = null;
        if (isset($msg->file)) {
            $file_info = [
                'name' => (string)$msg->file['name'],
                'path' => (string)$msg->file['path']
            ];
        }
        
        // CORRECTION: Utiliser un ID stable basé sur les données du message
        // Au lieu de uniqid(), utiliser un hash basé sur le contenu et timestamp
        $messageId = (string)$msg['id'] ?? md5($senderId . (string)$msg->text . (string)$msg['timestamp']);
        
        $messageText = (string)$msg->text;
        if (isset($msg['encrypted']) && (string)$msg['encrypted'] === 'true') {
            $messageText = decryptMessage($messageText);
        }
        
        $messages[] = [
            'id' => $messageId,
            'sender' => $senderId,
            'senderName' => $senderName,
            'text' => $messageText,
            'timestamp' => (string)$msg['timestamp'],
            'file' => $file_info,
            'isOwnMessage' => $senderId === $userId
        ];
    }
}

// Informations du groupe
$groupInfo = [
    'id' => $group_id,
    'name' => (string)$group->name,
    'memberCount' => count($group->members->member ?? [])
];

echo json_encode([
    'success' => true,
    'group' => $groupInfo,
    'messages' => $messages,
    'userId' => $userId
]);
?>