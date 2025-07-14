<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$userId = $_SESSION['email_id'] ?? null;
$group_id = $_GET['group'] ?? '';
if (!$userId || !$group_id) {
    echo '<div class="text-center text-gray-400 mt-12">Aucun message dans ce groupe.</div>';
    exit();
}
$groups_path = __DIR__ . '/../../storage/xml/groups.xml';
$users_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
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
                $isMe = $senderId === $userId;
                echo '<div class="flex ' . ($isMe ? 'justify-end' : 'justify-start') . ' mb-4">';
                echo '<div class="max-w-md px-4 py-2 rounded-2xl shadow-lg border ' . 
                    ($isMe ? 'bg-amber-200 dark:bg-amber-200 text-gray-900 border-amber-300 dark:border-amber-800 text-right' : 'bg-white dark:bg-gray-200 border-gray-200 dark:border-gray-700') . '">';
                echo '<div class="text-xs text-gray-500 mb-1 flex items-center gap-2 ' . ($isMe ? '' : 'justify-start') . '">';
                echo '<span class="' . ($isMe ? '' : 'font-bold text-gray-700 dark:text-gray-900') . '">' . htmlspecialchars($senderName) . '</span>';
                echo '<span>' . date('d/m H:i', strtotime((string)$msg['timestamp'])) . '</span>';
                echo '</div>';
                echo '<div class="text-sm text-gray-900 dark:text-gray-900 whitespace-pre-line">' . nl2br(htmlspecialchars((string)$msg->text)) . '</div>';
                if (isset($msg->file)) {
                    echo '<div class="mt-1"><a href="/whatsup/' . htmlspecialchars((string)$msg->file['path']) . '" target="_blank" class="text-amber-600 underline">Fichier: ' . htmlspecialchars((string)$msg->file['name']) . '</a></div>';
                }
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="text-center text-gray-400 mt-12">Aucun message dans ce groupe.</div>';
        }
        break;
    }
}
