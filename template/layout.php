<?php
/*
Ce layout doit être inclus avec $content défini avant (pour la partie principale).
Il suppose que la logique sidebar a préparé $contacts, $userDisplayName, $userAvatar
*/ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Espace protégé'; ?> - WhattsUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white dark:bg-gray-900">

<?php echo $content; ?>

    
</body>
</html>
