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
