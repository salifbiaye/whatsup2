<?php
/**
 * Modal de création de contact - Version Pro
 * Variables attendues : $modalId, $alert
 */
?>
<div id="<?php echo htmlspecialchars($modalId); ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg mx-4 relative overflow-hidden">
        <!-- Header avec dégradé -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">
                        Ajouter un contact
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
            <form method="post" action="/whatsup/chat_private" autocomplete="off" class="space-y-6">
                <input type="hidden" name="create_contact" value="1">
                
                <!-- Champ de recherche contact -->
                <div class="space-y-2">
                    <label for="contact_query" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Adresse email ou nom affiché du contact
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="contact_query"
                               name="contact_query"
                               required
                               placeholder="Entrez l'email ou le nom affiché..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-400">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Recherchez par adresse email ou nom d'utilisateur
                    </p>
                </div>
                
                <!-- Information d'aide -->
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                Comment ajouter un contact ?
                            </p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                Saisissez l'adresse email exacte ou le nom d'utilisateur de la personne que vous souhaitez ajouter à vos contacts.
                            </p>
                        </div>
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
                        Ajouter le contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>