<?php
session_start();

if (isset($_SESSION['email_id'])) {
    header('Location: /whatsup2/chat_private');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
        foreach ($users->user as $user) {
            if ((string)$user->email === $email && (string)$user->password === $password) {
                $_SESSION['email_id'] = (string)$user['id'];
                $_SESSION['email'] = (string)$user->email;
                header('Location: /whatsup2/chat_private');
                exit();
            }
        }
        $error = 'Identifiants invalides.';
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
include __DIR__ . '/../../template/auth/login.template.php';
