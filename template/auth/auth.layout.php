<?php ob_start(); ?>
<div class="flex min-h-screen w-full">
    <!-- Colonne gauche (formulaire) -->
    <div class="flex-1 flex items-center justify-center bg-white dark:bg-gray-900">
    <div class="w-full max-w-md mx-auto p-6">
        <?php echo $content; ?>
    </div>
    </div>
    <!-- Colonne droite décorative -->
    <div class="hidden md:flex w-1/2 relative bg-amber-100 dark:bg-white/5 items-center justify-center relative overflow-hidden">
    <div class="absolute right-0 top-0 -z-1 w-full max-w-[400px]">
        <img
          width="540"
          height="540"
          src="/whatsup2/assets/shape/grid-01.svg"
          alt="grid"
        />
      </div>
      <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[400px] rotate-180">
        <img
          width="540"
          height="540"
          src="/whatsup2/assets/shape/grid-01.svg"
          alt="grid"
        />
      </div>
    <div class="absolute opacity-80 "></div>
        <div class="relative z-10 flex flex-col items-center">
            <img src="/whatsup2/assets/logo/logo.png" alt="Logo" class="w-96 ">
  </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
