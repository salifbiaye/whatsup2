<?php
/**
 * Modal de création de groupe - Version Pro
 * Variables attendues : $modalId, $contacts ou $contacts_for_modal, $alert
 */
$membersList = $contacts_for_modal ?? $contacts ?? [];
?>
<div id="<?php echo htmlspecialchars($modalId); ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg mx-4 relative overflow-hidden">
        <!-- Header avec dégradé -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">
                        Créer un nouveau groupe
                    </h2>
                </div>
           
            </div>
            <!-- Décoration géométrique -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10"></div>
        </div>
       
        <!-- Contenu du modal -->
        <div class="p-6">
            <!-- Affichage des alertes -->
            <?php if (!empty($alert)): ?>
                <div class="mb-4">
                    <?php echo $alert; ?>
                </div>
            <?php endif; ?>
           
            <!-- Formulaire de création -->
            <form method="post" action="/whatsup/chat_group" autocomplete="off" class="space-y-6">
                <input type="hidden" name="create_group" value="1">
               
                <!-- Nom du groupe -->
                <div class="space-y-2">
                    <label for="group_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Nom du groupe
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="group_name"
                               name="group_name"
                               required
                               placeholder="Entrez le nom du groupe..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-400">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
               
                <!-- Sélection des membres -->
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Ajouter des membres
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 max-h-48 overflow-hidden">
                        <?php if (empty($membersList)): ?>
                            <div class="p-6 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    Aucun contact disponible
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-y-auto max-h-48">
                                <?php foreach ($membersList as $contact): ?>
                                    <label class="flex items-center gap-3 p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                        <div class="relative">
                                            <input type="checkbox"
                                                   name="members[]"
                                                   value="<?php echo htmlspecialchars($contact['id']); ?>"
                                                   class="sr-only peer">
                                            <div class="w-5 h-5 bg-white dark:bg-gray-600 border-2 border-gray-300 dark:border-gray-500 rounded peer-checked:bg-amber-500 peer-checked:border-amber-500 transition-all duration-200 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    <?php echo strtoupper(substr($contact['displayName'], 0, 1)); ?>
                                                </span>
                                            </div>
                                            <span class="text-gray-800 dark:text-gray-200 font-medium">
                                                <?php echo htmlspecialchars($contact['displayName']); ?>
                                            </span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
               
                <!-- Boutons d'action -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            onclick="closeModal('<?php echo htmlspecialchars($modalId); ?>')"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-white hover:from-amber-600 hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-200 font-medium shadow-lg">
                        Créer le groupe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>