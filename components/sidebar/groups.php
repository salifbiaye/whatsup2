<?php
// Variables attendues : $groups
?>
<?php if (!empty($groups)): ?>
    <div class="mt-6">
        <div class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold mb-2">Groupes</div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php foreach ($groups as $group): ?>
                <li>
                    <a href="/whatsup2/chat_group?group=<?php echo urlencode($group['id']); ?>" class="flex items-center gap-3 px-2 py-2 hover:bg-amber-50 dark:hover:bg-gray-800 rounded transition">
                        <span class="flex-1 text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($group['name']); ?></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo count($group['members']); ?> membres</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
