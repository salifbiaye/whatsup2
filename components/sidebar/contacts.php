<?php
// Variables attendues : $contacts
?>
<div class="flex-1 overflow-y-auto">
    <?php if (empty($contacts)): ?>
        <div class="text-gray-400 dark:text-gray-500 text-center mt-12">Aucun contact</div>
    <?php else: ?>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 contacts-list">
            <?php foreach ($contacts as $contact): ?>
                <li>
                    <a href="/whatsup2/chat_private?user=<?php echo urlencode($contact['id']); ?>" class="flex items-center gap-3 px-2 py-2 hover:bg-amber-50 dark:hover:bg-gray-800 rounded transition">
                        <img src="/whatsup2/<?php echo $contact['avatar'] ?: 'storage/avatars/avatar_default.png'; ?>" alt="<?php echo htmlspecialchars($contact['displayName']); ?>" class="w-8 h-8 rounded-full object-cover">
                        <span class="flex-1 text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($contact['displayName']); ?></span>
                        <span class="w-2 h-2 rounded-full <?php echo $contact['status'] === 'online' ? 'bg-green-500' : 'bg-gray-400'; ?>"></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
