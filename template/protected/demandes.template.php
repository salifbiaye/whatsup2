<div class="max-w-6xl mx-auto my-8 px-2">
<h1 class="flex flex-col items-center justify-center mb-8">
    <span class="inline-flex items-center gap-3">
        <svg class="w-9 h-9 text-amber-500 dark:text-gray-400 drop-shadow" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m10-5.13a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM7 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
        </svg>
        <span class="text-2xl md:text-3xl font-extrabold text-center tracking-tight dark:bg-gradient-to-r dark:from-gray-600 dark:via-gray-400 dark:to-gray-400 dark:bg-clip-text dark:text-transparent text-gray-600 drop-shadow">
            Gestion des demandes de contact
        </span>
    </span>
    <span class="mt-2 text-base md:text-lg text-gray-500 dark:text-gray-300 font-medium tracking-wide">
        Retrouvez ici toutes vos invitations, acceptations et refus de contact.
    </span>
</h1>

    <!-- Demandes en attente -->
    <section class="mb-8">
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur rounded-xl shadow-lg p-6">
            <h2 class="text-lg md:text-xl font-bold mb-4 text-yellow-700 dark:text-yellow-400 flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle></svg>
                Demandes en attente
            </h2>
            <ul>
            <?php foreach ($demandes_pending as $demande): ?>
                <li class="flex flex-col md:flex-row md:items-center justify-between p-4 mb-4 rounded-lg bg-yellow-50/80 dark:bg-yellow-900/20 border-l-4 border-yellow-400">
                    <div>
                        <span class="font-semibold text-yellow-800">De</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                            <?= htmlspecialchars($demande['email']) ?>
                        </span>
                        <span class="block text-xs text-gray-400 mt-1">
                            <?= htmlspecialchars($demande['date']) ?>
                        </span>
                    </div>
                    <form method="post" action="/whatsup/demandes" class="flex gap-2 mt-4 md:mt-0">
                        <input type="hidden" name="sender_id" value="<?= htmlspecialchars($demande['raw']->sender_id) ?>">
                        <button type="submit" name="action" value="accept" class="px-4 py-1 rounded bg-green-600 text-white font-semibold hover:bg-green-700 transition">Accepter</button>
                        <button type="submit" name="action" value="reject" class="px-4 py-1 rounded bg-red-600 text-white font-semibold hover:bg-red-700 transition">Refuser</button>
                    </form>
                </li>
            <?php endforeach; ?>
            <?php if (empty($demandes_pending)): ?>
                <li class="text-gray-500 italic px-4 py-2">Aucune demande en attente.</li>
            <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- Mes demandes envoyées -->
    <section class="mb-8">
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur rounded-xl shadow-lg p-6">
            <h2 class="text-lg md:text-xl font-bold mb-4 text-blue-700 dark:text-blue-400 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8V6a5 5 0 00-10 0v2M5 20h14a2 2 0 002-2V10a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Mes demandes envoyées
            </h2>
            <ul>
            <?php foreach ($demandes_envoyees as $demande): ?>
                <li class="flex flex-col md:flex-row md:items-center justify-between p-4 mb-4 rounded-lg bg-blue-50/80 dark:bg-blue-900/20 border-l-4 border-blue-400">
                    <div>
                        <span class="font-semibold text-blue-800">À</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                            <?= htmlspecialchars($demande['email']) ?>
                        </span>
                        <span class="block text-xs text-gray-400 mt-1">
                            <?= htmlspecialchars($demande['date']) ?>
                        </span>
                    </div>
                    <span class="px-4 py-1 rounded font-semibold
                        <?php if ($demande['status'] === 'pending') echo 'bg-yellow-600 text-white'; ?>
                        <?php if ($demande['status'] === 'accepted') echo 'bg-green-600 text-white'; ?>
                        <?php if ($demande['status'] === 'rejected') echo 'bg-red-600 text-white'; ?>
                    ">
                        <?php
                            if ($demande['status'] === 'pending') echo 'En attente';
                            elseif ($demande['status'] === 'accepted') echo 'Acceptée';
                            elseif ($demande['status'] === 'rejected') echo 'Refusée';
                        ?>
                    </span>
                </li>
            <?php endforeach; ?>
            <?php if (empty($demandes_envoyees)): ?>
                <li class="text-gray-500 italic px-4 py-2">Aucune demande envoyée.</li>
            <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- Demandes acceptées -->
    <section class="mb-8">
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur rounded-xl shadow-lg p-6">
            <h2 class="text-lg md:text-xl font-bold mb-4 text-green-700 dark:text-green-400 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path><circle cx="12" cy="12" r="10"></circle></svg>
                Demandes acceptées
            </h2>
            <ul>
            <?php foreach ($demandes_accepted as $demande): ?>
                <li class="flex flex-col md:flex-row md:items-center justify-between p-4 mb-4 rounded-lg bg-green-50/80 dark:bg-green-900/20 border-l-4 border-green-400">
                    <div>
                        <span class="font-semibold text-green-800">De</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
    <?= htmlspecialchars($demande['email']) ?>
</span>
<span class="block text-xs text-gray-400 mt-1">
    <?= htmlspecialchars($demande['date']) ?>
</span>
                    </div>
                    <span class="px-4 py-1 rounded bg-green-600 text-white font-semibold">Acceptée</span>
                </li>
            <?php endforeach; ?>
            <?php if (empty($demandes_accepted)): ?>
                <li class="text-gray-500 italic px-4 py-2">Aucune demande acceptée.</li>
            <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- Demandes refusées -->
    <section>
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur rounded-xl shadow-lg p-6">
            <h2 class="text-lg md:text-xl font-bold mb-4 text-red-700 dark:text-red-400 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path><circle cx="12" cy="12" r="10"></circle></svg>
                Demandes refusées
            </h2>
            <ul>
            <?php foreach ($demandes_rejected as $demande): ?>
                <li class="flex flex-col md:flex-row md:items-center justify-between p-4 mb-4 rounded-lg bg-red-50/80 dark:bg-red-900/20 border-l-4 border-red-400">
                    <div>
                        <span class="font-semibold text-red-800">De</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                            <?= htmlspecialchars($demande['email']) ?>
                        </span>
                        <span class="block text-xs text-gray-400 mt-1">
                            <?= htmlspecialchars($demande['date']) ?>
                        </span>
                    </div>
                    <span class="px-4 py-1 rounded bg-red-600 text-white font-semibold">Refusée</span>
                </li>
            <?php endforeach; ?>
            <?php if (empty($demandes_rejected)): ?>
                <li class="text-gray-500 italic px-4 py-2">Aucune demande refusée.</li>
            <?php endif; ?>
            </ul>
        </div>
    </section>
</div>