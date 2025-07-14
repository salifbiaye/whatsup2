
<?php if (empty($contact_id)): ?>
    <?php include __DIR__ . '/../../components/chat_private/empty.php'; ?>

<?php else: ?>
    <?php
        // Pour le JS, on passe le contactId en JS
        echo "<script>window.contactId = '" . addslashes($contact_id) . "';</script>\n";
        echo '<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js"></script>';
        echo '<script src="/whatsup/components/chat_private/chat.js"></script>';
        echo '<script src="/whatsup/components/chat_private/poll.js"></script>';
    ?>
    <?php include __DIR__ . '/../../components/chat_private/with_contact.php'; ?>
<?php endif; ?>
