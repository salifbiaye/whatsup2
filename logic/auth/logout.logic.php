<?php
session_start();
require_once __DIR__ . '/../utils.php';

// Mettre à jour le statut en offline
if (isset($_SESSION['email_id'])) {
    set_user_status($_SESSION['email_id'], 'offline');
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: /whatsup/login');
exit();
