<?php
/**
 * Modal de création de groupe
 * Variables attendues : $modalId, $contacts ou $contacts_for_modal, $alert
 */

$membersList = $contacts_for_modal ?? $contacts ?? [];
?>

<div id="<?php echo htmlspecialchars($modalId); ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <!-- Bouton de fermeture -->
        <button onclick="closeModal('<?php echo htmlspecialchars($modalId); ?>')" 
                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">
            &times;
        </button>
        
        <!-- Titre -->
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
            Créer un groupe
        </h2>
        
        <!-- Affichage des alertes -->
        <?php if (!empty($alert)): ?>
            <?php echo $alert; ?>
        <?php endif; ?>
        
        <!-- Formulaire de création -->
        <form method="post" action="/whatsup2/chat_group" autocomplete="off">
            <input type="hidden" name="create_group" value="1">
            
            <!-- Nom du groupe -->
            <div class="mb-4">
                <label for="group_name" class="block mb-2 text-gray-700 dark:text-gray-200">
                    Nom du groupe
                </label>
                <input type="text" 
                       id="group_name" 
                       name="group_name" 
                       required 
                       class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100">
            </div>
            
            <!-- Sélection des membres -->
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 dark:text-gray-200">
                    Membres (parmi vos contacts)
                </label>
                <div class="max-h-40 overflow-y-auto">
                    <?php if (empty($membersList)): ?>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            Aucun contact disponible
                        </p>
                    <?php else: ?>
                        <?php foreach ($membersList as $contact): ?>
                            <label class="flex items-center gap-2 mb-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                                <input type="checkbox" 
                                       name="members[]" 
                                       value="<?php echo htmlspecialchars($contact['id']); ?>" 
                                       class="accent-amber-600">
                                <span class="text-gray-800 dark:text-gray-100">
                                    <?php echo htmlspecialchars($contact['displayName']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="flex justify-end gap-2">
                <button type="button" 
                        onclick="closeModal('<?php echo htmlspecialchars($modalId); ?>')" 
                        class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    Créer le groupe
                </button>
            </div>
        </form>
    </div>
</div>