<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email_id'])) {
    header('Location: /whatsup2/login');
    exit;
}

// Chemin vers le fichier XML des utilisateurs
$xmlPath = __DIR__ . '/../storage/xml/users.xml';

// Charger le fichier XML
if (file_exists($xmlPath)) {
    $users = simplexml_load_file($xmlPath);
    
    // Mettre à jour le statut de l'utilisateur à offline
    foreach ($users->user as $user) {
        if ((string)$user['id'] === $_SESSION['email_id']) {
            $user->status = 'offline';
            break;
        }
    }
    
    // Sauvegarder les modifications
    $users->asXML($xmlPath);
}

// Détruire la session
session_destroy();

// Redirection vers la page de login
header('Location: /whatsup2/login');
exit;