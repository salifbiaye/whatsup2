<?php
/**
 * Modal de mise à jour utilisateur - Version Pro
 * Variables attendues : $modalId, $user
 */
if (!isset($modalId)) $modalId = 'modalUpdateUser';
?>
<div id="<?php echo htmlspecialchars($modalId); ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg mx-4 relative overflow-hidden">
        <!-- Header avec dégradé -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-white">
                        Modifier mon profil
                    </h2>
                </div>
            </div>
            <!-- Décoration géométrique -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10"></div>
        </div>
        
        <!-- Contenu du modal -->
        <div class="p-6">
            <!-- Formulaire de mise à jour -->
            <form method="POST" action="/whatsup/logic/auth/update_user.logic.php" enctype="multipart/form-data" class="space-y-6">
                
                <!-- Aperçu de l'avatar actuel -->
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-2xl">
                                <?php echo strtoupper(substr($user['displayName'] ?? 'U', 0, 1)); ?>
                            </span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                    </div>
                </div>
                
                <!-- Adresse email (lecture seule) -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Adresse email
                    </label>
                    <div class="relative">
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                            <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        L'adresse email ne peut pas être modifiée
                    </p>
                </div>
                
                <!-- Nom affiché -->
                <div class="space-y-2">
                    <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Nom affiché
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="display_name"
                               name="display_name"
                               value="<?php echo htmlspecialchars($user['displayName'] ?? ''); ?>"
                               required
                               placeholder="Entrez votre nom affiché..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-400">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Upload d'avatar -->
                <div class="space-y-2">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Avatar
                    </label>
                    <div class="relative">
                        <input type="file"
                               id="avatar"
                               name="avatar"
                               accept="image/*"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Formats acceptés : JPG, PNG, GIF (max 2MB)
                    </p>
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
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Gestion de la réponse du formulaire de mise à jour
const form = document.querySelector('#<?php echo htmlspecialchars($modalId); ?> form');
if (form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        
        // Ajouter un indicateur de chargement
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Enregistrement...';
        submitButton.disabled = true;
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour le header avec le nouveau nom d'affichage
                const headerDisplayName = document.querySelector('.font-semibold');
                if (headerDisplayName) {
                    headerDisplayName.textContent = formData.get('display_name');
                }
                
                // Mettre à jour l'avatar si nécessaire
                const avatarInput = form.querySelector('input[type="file"]');
                if (avatarInput.files.length > 0) {
                    const avatarImg = document.querySelector('img');
                    if (avatarImg) {
                        avatarImg.src = '/whatsup/' + formData.get('avatar');
                    }
                }
                
                // Fermer le modal
                closeModal('<?php echo htmlspecialchars($modalId); ?>');
                
                // Afficher un message de succès
                showNotification('Profil mis à jour avec succès !', 'success');
                
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        } catch (error) {
            showNotification('Une erreur est survenue lors de la mise à jour du profil', 'error');
        } finally {
            // Remettre le bouton en état normal
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    });
}

// Fonction pour afficher les notifications (à implémenter selon votre système)
function showNotification(message, type) {
    // Implémentation basique - à adapter selon votre système de notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>