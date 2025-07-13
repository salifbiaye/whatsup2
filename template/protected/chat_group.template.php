<?php
/**
 * Template principal du chat de groupe
 * Variables attendues : $group_id, $group, $messages, $members, $userId
 */

// Vérification si un groupe est sélectionné
if (empty($group_id) || !isset($group)) {
    // Inclusion de la page vide
    include __DIR__ . '/../../components/chat_group/empty.php';
} else {
    // Configuration JavaScript pour le polling
    $safe_group_id = addslashes($group_id);
    $safe_user_id = addslashes($userId ?? $_SESSION['email_id'] ?? '');
    ?>
    
    <script>
        window.groupId = '<?php echo $safe_group_id; ?>';
        window.userId = '<?php echo $safe_user_id; ?>';
    </script>
    
    <script src="/whatsup2/components/chat_group/poll.js"></script>
    
    <?php
    // Inclusion de la page avec groupe
    include __DIR__ . '/../../components/chat_group/with_group.php';
}
?>