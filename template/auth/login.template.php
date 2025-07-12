<?php ob_start(); ?>
<div>
    <div class="mb-8">
        <h1 class="mb-2 font-semibold text-gray-800 text-2xl dark:text-white/90">Connexion</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Entrez votre email et mot de passe pour vous connecter !
        </p>
    </div>
    <?php if (!empty($error)): ?>
        <div class="text-red-600 mb-4 text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-6">
        <div>
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Adresse email <span class="text-red-500">*</span></label>
            <input type="text" name="email" placeholder="Adresse email" required autofocus
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Mot de passe <span class="text-red-500">*</span></label>
            <input type="password" name="password" placeholder="Mot de passe" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
        <button type="submit"
            class="w-full py-2 bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors">Se connecter</button>
    </form>
    <div class="mt-5 text-center">
        <p class="text-sm text-gray-700 dark:text-gray-400">
            Pas encore de compte ?
            <a href="/whatsup2/register" class="text-amber-500 hover:text-amber-600 dark:text-amber-400">Créer un compte</a>
        </p>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php $title = "Connexion"; ?>
<?php include __DIR__ . '/auth.layout.php'; ?>
