<?php 
// Variables attendues : $userAvatar, $userDisplayName, $status 
?>
<div class="flex items-center justify-between gap-3 mb-6 p-4 bg-gray-100/20 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
    <!-- Informations utilisateur -->
    <div class="flex items-center gap-3 flex-1 min-w-0">
        <div class="relative">
            <img src="/whatsup/<?php echo $userAvatar ?: 'storage/avatars/avatar_default.png'; ?>" 
                 alt="Avatar" 
                 class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm">
            <!-- Indicateur de statut -->
            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-700 <?php echo isset($status) && $status === 'online' ? 'bg-green-500' : 'bg-gray-400'; ?>"></div>
        </div>
        
        <div class="flex flex-col min-w-0 flex-1">
            <div class="font-semibold text-gray-900 dark:text-white truncate">
                <?php echo htmlspecialchars($userDisplayName); ?>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                <div class="w-2 h-2 rounded-full <?php echo isset($status) && $status === 'online' ? 'bg-green-500' : 'bg-gray-400'; ?>"></div>
                <?php echo isset($status) && $status === 'online' ? 'En ligne' : 'Hors ligne'; ?>
            </div>
        </div>
    </div>
    
    <!-- Menu utilisateur -->
    <div class="relative">
        <button id="user-menu-toggle" 
                class="flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm border border-gray-200 dark:border-gray-600 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
        
        <!-- Menu déroulant -->
        <div id="user-menu" 
             class="absolute right-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-600 hidden z-50 overflow-hidden">
            
            <!-- En-tête du menu -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    <?php echo htmlspecialchars($userDisplayName); ?>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <?php echo isset($status) && $status === 'online' ? 'En ligne' : 'Hors ligne'; ?>
                </p>
            </div>
            
            <!-- Options du menu -->
            <div class="py-2">
                <a href="#" 
                   onclick="openModal('modalUpdateUser'); toggleUserMenu(); return false;" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <span>Modifier le profil</span>
                </a>
                
                <a href="/whatsup/demandes" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <span>Demandes d'amis</span>
                </a>
                
                <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                
                <a href="/whatsup/logout" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    menu.classList.toggle('hidden');
}

// Toggle menu au clic du bouton
document.getElementById('user-menu-toggle')?.addEventListener('click', function(e) {
    e.stopPropagation();
    toggleUserMenu();
});

// Fermer le menu si on clique ailleurs
document.addEventListener('click', function(e) {
    const menu = document.getElementById('user-menu');
    const toggle = document.getElementById('user-menu-toggle');
    
    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>