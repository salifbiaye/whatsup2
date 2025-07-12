<?php
// Pas de variables attendues spécifiques
?>
<div class="mb-6">
    <button class="w-full flex items-center justify-between px-3 py-2 bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors dark:bg-amber-700 dark:hover:bg-amber-800" onclick="document.getElementById('dropdown').classList.toggle('hidden')">
        Actions
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div id="dropdown" class="hidden mt-2 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded shadow-md">
        <a href="#" onclick="openModal('modalCreateContact');return false;" class="block px-4 py-2 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">Créer un contact</a>
        <a href="#" onclick="openModal('modalCreateGroup');return false;" class="block px-4 py-2 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">Créer un groupe</a>
    </div>
</div>
