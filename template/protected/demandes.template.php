<div class="max-w-7xl mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-4 p-6 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border border-amber-200 dark:border-amber-800">
            <div class="p-3 bg-amber-100 dark:bg-amber-800 rounded-xl">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m10-5.13a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM7 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
                </svg>
            </div>
            <div class="text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Gestion des demandes
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">
                    Gérez vos invitations et demandes de contact
                </p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">En attente</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                        <?= count($demandes_pending) ?>
                    </p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-full">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Envoyées</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        <?= count($demandes_envoyees) ?>
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 8V6a5 5 0 00-10 0v2M5 20h14a2 2 0 002-2V10a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Acceptées</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        <?= count($demandes_accepted) ?>
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path><circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Refusées</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        <?= count($demandes_rejected) ?>
                    </p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path><circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes en attente -->
    <section class="mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                    Demandes en attente
                    <?php if (count($demandes_pending) > 0): ?>
                        <span class="bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400 px-2 py-1 rounded-full text-sm font-medium">
                            <?= count($demandes_pending) ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($demandes_pending)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Aucune demande en attente</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($demandes_pending as $demande): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($demande['email']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= htmlspecialchars($demande['date']) ?>
                                        </p>
                                    </div>
                                </div>
                                <form method="post" action="/whatsup/demandes" class="flex gap-2">
                                    <input type="hidden" name="sender_id" value="<?= htmlspecialchars($demande['raw']->sender_id) ?>">
                                    <button type="submit" name="action" value="accept" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                        Accepter
                                    </button>
                                    <button type="submit" name="action" value="reject" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors font-medium">
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Mes demandes envoyées -->
    <section class="mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    Mes demandes envoyées
                    <?php if (count($demandes_envoyees) > 0): ?>
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-2 py-1 rounded-full text-sm font-medium">
                            <?= count($demandes_envoyees) ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($demandes_envoyees)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 8V6a5 5 0 00-10 0v2M5 20h14a2 2 0 002-2V10a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Aucune demande envoyée</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($demandes_envoyees as $demande): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($demande['email']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= htmlspecialchars($demande['date']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <?php
                                    $statusConfig = [
                                        'pending' => ['bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400', 'En attente'],
                                        'accepted' => ['bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400', 'Acceptée'],
                                        'rejected' => ['bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400', 'Refusée']
                                    ];
                                    $config = $statusConfig[$demande['status']] ?? ['bg-gray-100 text-gray-600', 'Inconnu'];
                                    ?>
                                    <span class="<?= $config[0] ?> px-3 py-1 rounded-full text-sm font-medium">
                                        <?= $config[1] ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Demandes acceptées -->
    <section class="mb-8">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    Demandes acceptées
                    <?php if (count($demandes_accepted) > 0): ?>
                        <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-2 py-1 rounded-full text-sm font-medium">
                            <?= count($demandes_accepted) ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($demandes_accepted)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path><circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Aucune demande acceptée</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($demandes_accepted as $demande): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($demande['email']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= htmlspecialchars($demande['date']) ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-3 py-1 rounded-full text-sm font-medium">
                                    Acceptée
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Demandes refusées -->
    <section>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    Demandes refusées
                    <?php if (count($demandes_rejected) > 0): ?>
                        <span class="bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 px-2 py-1 rounded-full text-sm font-medium">
                            <?= count($demandes_rejected) ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($demandes_rejected)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path><circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Aucune demande refusée</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($demandes_rejected as $demande): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?= htmlspecialchars($demande['email']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= htmlspecialchars($demande['date']) ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 px-3 py-1 rounded-full text-sm font-medium">
                                    Refusée
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>