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