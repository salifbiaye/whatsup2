<?php
session_start();
if (isset($_SESSION['email_id'])) {
    header('Location: /whatsup/chat_private');
    exit();
}
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $displayName = trim($_POST['displayName'] ?? '');
    $avatar = 'storage/avatars/avatar_default.png';
    $status = 'offline';
    if ($email && $password && $displayName) {
        $users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
        $exists = false;
        foreach ($users->user as $u) {
            if ((string)$u->email === $email) {
                $exists = true;
                break;
            }
        }
        if ($exists) {
            $error = "Ce email existe déjà.";
        } else {
            $new_id = 'u' . (count($users->user) + 1);
            $user = $users->addChild('user');
            $user->addAttribute('id', $new_id);
            $user->addChild('email', $email);
            $user->addChild('password', $password); // À remplacer par un hash en prod
            $user->addChild('displayName', $displayName);
            $user->addChild('avatar', $avatar);
            $user->addChild('status', $status);
            $user->addChild('contacts');
            $users->asXML(__DIR__ . '/../../storage/xml/users.xml');
            $success = 'Compte créé ! Vous pouvez maintenant vous connecter.';
            header('Refresh:2; url=login');
        }
    } else {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    }
}
include __DIR__ . '/../../template/auth/register.template.php';
