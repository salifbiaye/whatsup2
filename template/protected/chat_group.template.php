<?php
// Variables attendues : $group_id, $group, $messages, $members, $userId
ob_start();
if (empty($group_id)) {
    include __DIR__ . '/../../components/chat_group/empty.php';
} else {
    echo "<script>window.groupId = '" . addslashes($group_id) . "'; window.userId = '" . addslashes($_SESSION['email_id']) . "';</script>\n";
    echo '<script src="/whatsup2/components/chat_group/poll.js"></script>';
    include __DIR__ . '/../../components/chat_group/with_group.php';
}
$content = ob_get_clean();
?>
