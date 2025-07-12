<?php
// Modal création de groupe
// Props attendues : $modalId, $contacts ou $contacts_for_modal
$membersList = isset($contacts_for_modal) ? $contacts_for_modal : ($contacts ?? []);
?>
<div id="<?php echo $modalId; ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 w-full max-w-md relative">
    <button onclick="closeModal('<?php echo $modalId; ?>')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Créer un groupe</h2>
    <?php if (!empty($alert)) echo $alert; ?>
    <form method="post" action="" autocomplete="off">
      <input type="hidden" name="create_group" value="1">
      <label class="block mb-2 text-gray-700 dark:text-gray-200">Nom du groupe</label>
      <input type="text" name="group_name" required class="w-full px-3 py-2 mb-4 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100">
      <label class="block mb-2 text-gray-700 dark:text-gray-200">Membres (parmi vos contacts)</label>
      <div class="max-h-40 overflow-y-auto mb-4">
        <?php foreach ($membersList as $contact): ?>
        <label class="flex items-center gap-2 mb-1">
          <input type="checkbox" name="members[]" value="<?php echo htmlspecialchars($contact['id']); ?>" class="accent-amber-600">
          <span class="text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($contact['displayName']); ?></span>
        </label>
        <?php endforeach; ?>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('<?php echo $modalId; ?>')" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600">Annuler</button>
        <button type="submit" class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700">Ajouter</button>
      </div>
    </form>
  </div>
</div>
