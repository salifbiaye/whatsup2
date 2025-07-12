<?php ob_start(); ?>
    <!-- Sidebar -->
    <aside class="w-72 bg-white dark:bg-gray-900/30 border-r border-gray-200 dark:border-gray-700 flex flex-col h-screen p-4 fixed left-0 top-0 z-30">
        <?php
        // Inclusion des composants de la sidebar
        include __DIR__ . '/../../components/sidebar/header.php';
        include __DIR__ . '/../../components/sidebar/search.php';
        include __DIR__ . '/../../components/sidebar/actions.php';
        include __DIR__ . '/../../components/sidebar/contacts.php';
        include __DIR__ . '/../../components/sidebar/groups.php';
        ?>
    </aside>
    <!-- Main content -->
    <main class="flex-1  ml-72">
        <?php echo $content; ?>
        <?php 
        // Passer les données utilisateur au modal
        $modal_contacts = $contacts;
        $user = $modal_user ?? [];
        include __DIR__ . '/../../components/sidebar/modals.php'; 
        ?>
    </main>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
