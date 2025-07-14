<?php ob_start(); ?>

<!-- Overlay pour mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 flex flex-col h-screen p-4 fixed left-0 top-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:z-30">
    <!-- Bouton fermer pour mobile -->
    <button id="close-sidebar" class="absolute top-4 right-4 lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    
    <?php
    // Inclusion des composants de la sidebar
    include __DIR__ . '/../../components/sidebar/header.php';
    include __DIR__ . '/../../components/sidebar/search.php';
    include __DIR__ . '/../../components/sidebar/actions.php';
    include __DIR__ . '/../../components/sidebar/contacts.php';
    include __DIR__ . '/../../components/sidebar/groups.php';
    ?>
</aside>

<!-- Header mobile avec bouton menu -->
<header class="lg:hidden fixed top-0 left-0  right-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 p-4 z-20">
    <div class="flex items-center justify-between">
        <button id="open-sidebar" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Messages</h1>
        <div class="w-6"></div> <!-- Spacer pour centrer le titre -->
    </div>
</header>

<!-- Main content -->
<main class="flex-1 lg:ml-72 pt-16 lg:pt-0">
    <?php echo $content; ?>
    <?php 
    // Passer les données utilisateur au modal
    $modal_contacts = $contacts;
    $user = $modal_user ?? [];
    include __DIR__ . '/../../components/sidebar/modals.php';
    ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    
    // Fonction pour ouvrir la sidebar
    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // Empêche le scroll du body
    }
    
    // Fonction pour fermer la sidebar
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Event listeners
    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
    
    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });
    
    // Fermer automatiquement sur les écrans larges
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) { // lg breakpoint
            closeSidebar();
        }
    });
});
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>