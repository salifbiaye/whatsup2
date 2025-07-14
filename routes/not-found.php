<?php
// Route pour 404 Not Found
http_response_code(404);
$title = 'Page non trouvée';
$content = '';
ob_start();
?>
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-amber-100 via-white to-amber-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
  <div class="text-center">
    <svg class="mx-auto mb-6 w-24 h-24 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48">
      <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="3" fill="none"/>
      <path d="M16 20c0-4 8-4 8 0v4c0 4-8 4-8 0v-4z" stroke="currentColor" stroke-width="2"/>
      <circle cx="18" cy="18" r="2" fill="currentColor"/>
      <circle cx="30" cy="18" r="2" fill="currentColor"/>
    </svg>
    <h1 class="text-6xl font-extrabold text-amber-600 dark:text-amber-400 mb-4">404</h1>
    <p class="text-2xl text-gray-700 dark:text-gray-200 mb-6">Oups ! Cette page n'existe pas.</p>
    <a href="/whatsup/login" class="inline-block px-6 py-3 bg-amber-600 text-white rounded-lg shadow hover:bg-amber-700 transition text-lg font-semibold">Retour à l'accueil</a>
  </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../template/layout.php';
