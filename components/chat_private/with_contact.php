<div class="mx-auto bg-white h-screen dark:bg-white/5 shadow p-6">
    <div class="flex items-center gap-3 mb-4">
        <img src="/whatsup2/<?php echo $contactAvatar!='' ? $contactAvatar : 'storage/avatars/avatar_default.png'; ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover border">
        <div>
            <div class="font-semibold text-gray-800 dark:text-gray-100"><?php echo $contactDisplayName; ?></div>
            <div class="text-xs text-gray-400 dark:text-gray-400">Chat privé</div>
        </div>
    </div>
    <div class="messages h-[calc(100vh-220px)] overflow-y-auto mb-4 px-8 py-4 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded" id="messages-box">
        <?php if (empty($messages)): ?>
            <div class="text-gray-400 text-center">Aucun message.</div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="mb-3 <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'text-right' : 'text-left'; ?>">
                    <div class="inline-block <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'bg-amber-100 dark:bg-amber-900/60' : 'bg-gray-200 dark:bg-gray-700'; ?> px-4 py-2 rounded shadow">
                        <div class="text-sm text-gray-800 dark:text-gray-100"><?php echo nl2br(htmlspecialchars($msg['text'])); ?></div>
                        <?php if ($msg['file']): ?>
                            <a class="block mt-1 text-xs text-amber-600 hover:underline" href="/whatsup2/<?php echo htmlspecialchars(str_replace('storage/', '/storage/', $msg['file']['path'])); ?>" target="_blank">📎 <?php echo htmlspecialchars($msg['file']['name']); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-400 mt-1">
                        <?php
                        echo $msg['sender'] === $_SESSION['email_id'] ? 'Moi' : (isset($msg['senderName']) ? $msg['senderName'] : $msg['sender']);
                        echo ' | ' . htmlspecialchars(date('d/m/Y H:i', strtotime($msg['timestamp'])));
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/form.php'; ?>
</div>
