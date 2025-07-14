<?php
// Modal création de contact
// Props attendues : $modalId
?>
<div id="<?php echo $modalId; ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 w-full max-w-md relative">
    <button onclick="closeModal('<?php echo $modalId; ?>')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Ajouter un contact</h2>
    <?php if (!empty($alert)) echo $alert; ?>
    <form method="post" action="/whatsup/chat_private" autocomplete="off">
      <input type="hidden" name="create_contact" value="1">
      <label class="block mb-2 text-gray-700 dark:text-gray-200">Adresse email ou nom affiché du contact</label>
      <input type="text" name="contact_query" required placeholder="Email ou nom affiché" class="w-full px-3 py-2 mb-4 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('<?php echo $modalId; ?>')" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600">Annuler</button>
        <button type="submit" class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700">Ajouter</button>
      </div>
    </form>
  </div>
</div>
