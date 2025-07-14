<?php
session_start();

if (isset($_SESSION['email_id'])) {
    header('Location: /whatsup/chat_private');
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
                $user->status = 'online';
                $users->asXML(__DIR__ . '/../../storage/xml/users.xml');

                header('Location: /whatsup/chat_private');
                exit();
            }
        }
        $error = 'Identifiants invalides.';
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
include __DIR__ . '/../../template/auth/login.template.php';
