<?php
// Variables attendues : $group (SimpleXMLElement), $messages, $members, $userId
?>
<div class="mx-auto bg-white h-screen dark:bg-white/5 shadow p-6">
    <div class="flex items-center gap-3 mb-4">
        <img src="/whatsup/storage/avatars/avatar_group.png" alt="Avatar Groupe" class="w-10 h-10 rounded-full object-cover border">
        <div>
            <div class="font-semibold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars((string)$group->name); ?></div>
            <div class="text-xs text-gray-400 dark:text-gray-400">Chat de groupe</div>
        </div>
    </div>
  <!-- Header -->
  <div class="flex items-center gap-3 px-4  border-gray-200 dark:border-gray-700  ">
    <div class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex-1">
      <?php echo htmlspecialchars((string)$group->name); ?>
    </div>
    <div class="text-xs text-gray-500 dark:text-gray-400">
      <?php echo count($members); ?> membres
    </div>
  </div>
  <!-- Messages -->
  <div id="messages-box" class="messages h-[calc(100vh-220px)] overflow-y-auto mb-4 px-8 py-4 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded">
    <?php if (empty($messages)): ?>
      <div class="text-center text-gray-400 mt-12">Aucun message dans ce groupe.</div>
    <?php else: ?>
      <?php foreach ($messages as $msg): ?>
        <div class="flex <?php echo $msg['sender'] === $userId ? 'justify-end' : 'justify-start'; ?> mb-4">
          <div class="max-w-md px-4 py-2 rounded-2xl shadow-lg border <?php echo $msg['sender'] === $userId ? 'bg-amber-200 dark:bg-amber-700/60 border-amber-300 dark:border-amber-800 text-right' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700'; ?>">
            <div class="text-xs text-gray-500 mb-1 flex items-center gap-2 <?php echo $msg['sender'] === $userId ? '' : 'justify-start'; ?>">
              <span class="<?php echo $msg['sender'] === $userId ? '' : 'font-bold text-gray-700 dark:text-gray-200'; ?>"><?php echo htmlspecialchars($msg['senderName']); ?></span>
              <span><?php echo date('d/m H:i', strtotime($msg['timestamp'])); ?></span>
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($msg['text'])); ?></div>
            <?php if (!empty($msg['file'])): ?>
              <div class="mt-1"><a href="/<?php echo htmlspecialchars($msg['file']['path']); ?>" target="_blank" class="text-amber-600 underline">Fichier: <?php echo htmlspecialchars($msg['file']['name']); ?></a></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <!-- Formulaire d'envoi -->
  <?php include __DIR__ . '/form.php'; ?>
</div>
