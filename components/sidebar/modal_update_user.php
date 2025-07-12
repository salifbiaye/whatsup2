<?php
// Modal de mise à jour utilisateur
// Props attendues : $modalId (string, identifiant du modal), $user (array)
if (!isset($modalId)) $modalId = 'modalUpdateUser';
?>
<div id="<?php echo $modalId; ?>" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <button onclick="closeModal('<?php echo $modalId; ?>')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Modifier mon profil</h2>
        <form method="POST" action="/whatsup2/logic/auth/update_user.logic.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Adresse email</label>
                <div class="w-full px-3 py-2 mb-4 dark:text-white rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Nom affiché</label>
                <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['displayName'] ?? ''); ?>" class="w-full px-3 py-2 mb-4 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Avatar</label>
                <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 mb-4 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('<?php echo $modalId; ?>')" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded bg-amber-600 text-white hover:bg-amber-700">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion de la réponse du formulaire de mise à jour
const form = document.querySelector('#<?php echo $modalId; ?> form');
if (form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
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
                        avatarImg.src = '/whatsup2/' + formData.get('avatar');
                    }
                }
                
                // Fermer le modal
                closeModal('<?php echo $modalId; ?>');
                
            } else {
                alert('Erreur: ' + data.message);
            }
        } catch (error) {
            alert('Une erreur est survenue lors de la mise à jour du profil');
        }
    });
}
</script>
        </form>
    </div>
</div>
