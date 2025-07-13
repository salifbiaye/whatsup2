<?php
// Variables attendues : $userAvatar, $userDisplayName, $status
?>
<div class="flex items-center justify-between gap-3 mb-6">
  <div class="flex items-center gap-2 flex-row">
        <img src="/whatsup2/<?php echo $userAvatar ?: 'storage/avatars/avatar_default.png'; ?>" alt="Avatar" class="w-12 h-12 rounded-full object-cover border">
        <div class="flex flex-col">
            <div class="font-semibold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($userDisplayName); ?></div>
            <div class="text-xs text-gray-400 dark:text-gray-400"><?php echo isset($status) && $status === 'online' ? 'Connecté' : 'Déconnecté'; ?></div>
        </div>
</div>
    <div class="relative z-50">
        <button class="mt-2 px-3 py-1 rounded bg-amber-600 text-white text-xs hover:bg-amber-700 transition flex items-center gap-1" onclick="document.getElementById('user-menu').classList.toggle('hidden')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg hidden">
            <a href="#" onclick="openModal('modalUpdateUser');return false;" class="block px-4 text-sm py-2 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                profil
            </a>
            <a href="/whatsup2/demandes"  class="block px-4 text-sm py-2 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Demandes
            </a>
            <a href="/whatsup2/logout" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
            </a>
        </div>
    </div>
</div>
