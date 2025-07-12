<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$userId = $_SESSION['email_id'] ?? null;
$contacts = [];
$groups = [];
$userDisplayName = '';
$userAvatar = '';
$status = 'offline';
if ($userId) {
    $users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
    foreach ($users->user as $user) {
        if ((string)$user['id'] === $userId) {
            // Préparer les données utilisateur pour le modal
            $userDisplayName = (string)$user->displayName;
            $userAvatar = (string)$user->avatar;
            $userEmail = (string)$user->email;
            $status = 'online';
            
            // Préparer les données pour le modal
            $modal_user = [
                'email' => $userEmail,
                'displayName' => $userDisplayName,
                'avatar' => $userAvatar
            ];
            
            foreach ($user->contacts->contact as $contactId) {
                foreach ($users->user as $u) {
                    if ((string)$u['id'] === (string)$contactId) {
                        $contacts[] = [
                            'id' => (string)$u['id'],
                            'displayName' => (string)$u->displayName,
                            'avatar' => (string)$u->avatar,
                            'status' => (string)$u->status
                        ];
                    }
                }
            }
            break;
        }
    }
    // Ajout des groupes où je suis membre
    $groups_path = __DIR__ . '/../../storage/xml/groups.xml';
    if (file_exists($groups_path)) {
        $groups_xml = simplexml_load_file($groups_path);
        foreach ($groups_xml->group as $g) {
            $members = (array)$g->members->member;
            $member_ids = array_map('strval', $members);
            if (in_array($userId, $member_ids)) {
                $groups[] = [
                    'id' => (string)$g['id'],
                    'name' => (string)$g->name,
                    'members' => $member_ids,
                ];
            }
        }
    }
}
