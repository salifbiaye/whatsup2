<?php
function set_user_status($email_id, $status = 'online') {
    $users_file = __DIR__ . '/storage/xml/users.xml';
    if (!file_exists($users_file)) return;
    $users = simplexml_load_file($users_file);
    foreach ($users->user as $u) {
        if ((string)$u['id'] === $email_id) {
            $u->status = $status;
            $users->asXML($users_file);
            break;
        }
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function send_gmail_notification($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Paramètres SMTP Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'goldlif94@gmail.com'; // Ton email Gmail
        $mail->Password = 'nfuqmelgszfburgz'; // Mot de passe ou App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('goldlif94@gmail.com', 'WhatsUp');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}




// Clé de chiffrement (à changer en production)
define('ENCRYPTION_KEY', 'Kj9#mP2$vL5&qX8!zW3*tB6(nQ9)hM4^cV7%jK1@lN8&pX5$sR2)vY9&hT4#gB7*nM2@kL5$qW8!zX3^pV6%jH9(mK4&cF7#sD2*');

function getEncryptionKey() {
    return ENCRYPTION_KEY;
}

function encryptMessage($message) {
    $key =  getEncryptionKey();
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($message, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptMessage($encryptedMessage) {
    $key = getEncryptionKey();
    $data = base64_decode($encryptedMessage);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}