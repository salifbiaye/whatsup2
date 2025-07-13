<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['email_id']; // ou autre identifiant

$xml_file = __DIR__ . '/../../storage/xml/demandes.xml';
$users_file = __DIR__ . '/../../storage/xml/users.xml';

function process_demande_action($user_id, $sender_id, $action, $xml_file, $users_file) {
    $xml = simplexml_load_file($xml_file);
    $updated = false;
    foreach ($xml->demande as $demande) {
        if ((string)$demande->sender_id === $sender_id && (string)$demande->receiver_id === (string)$user_id && (string)$demande->status === 'pending') {
            $demande->status = ($action === 'accept') ? 'accepted' : 'rejected';
            $updated = true;
            break;
        }
    }
    $xml->asXML($xml_file);
    if ($updated && $action === 'accept') {
        $users_xml = simplexml_load_file($users_file);
        foreach ($users_xml->user as $user) {
            if ((string)$user['id'] === (string)$user_id) {
                if (!isset($user->contacts)) $user->addChild('contacts');
                $already = false;
                foreach ($user->contacts->contact as $c) {
                    if ((string)$c == $sender_id) {
                        $already = true;
                        break;
                    }
                }
                if (!$already) {
                    $user->contacts->addChild('contact', $sender_id);
                }
                break;
            }
        }
        $users_xml->asXML($users_file);
    }
}

function get_demandes_by_status($xml, $user_id, $status) {
    $result = [];
    foreach ($xml->demande as $demande) {
        if ((string)$demande->receiver_id === (string)$user_id && (string)$demande->status === $status) {
            $result[] = $demande;
        }
    }
    return $result;
}

function get_sent_demandes($xml, $user_id) {
    $result = [];
    foreach ($xml->demande as $demande) {
        if ((string)$demande->sender_id === $user_id) {
            $result[] = $demande;
        }
    }
    return $result;
}

function enrichir_demandes($demandes, $user_emails, $type = 'received') {
    $result = [];
    foreach ($demandes as $demande) {
        if ($type === 'received') {
            $id = (string)$demande->sender_id;
        } else {
            $id = (string)$demande->receiver_id;
        }
        $result[] = [
            'email' => $user_emails[$id] ?? $id,
            'date' => format_date_humaine((string)$demande->date),
            'status' => (string)$demande->status,
            'raw'  => $demande
        ];
    }
    return $result;
}

function format_date_humaine($iso_date) {
    $dt = new DateTime($iso_date);
    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR');
    return strftime('%e %B %Y, %H:%M', $dt->getTimestamp());
}


$user_id = $_SESSION['email_id'];
$xml = simplexml_load_file($xml_file);
$users_xml = simplexml_load_file($users_file);
$user_emails = [];
foreach ($users_xml->user as $user) {
    $user_emails[(string)$user['id']] = (string)$user->email;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sender_id'], $_POST['action'])) {
    $sender_id = $_POST['sender_id'];
    $action = $_POST['action'];
    process_demande_action($user_id, $sender_id, $action, $xml_file, $users_file);
    header('Location: /whatsup2/demandes');
    exit;
}

// Préparation des listes enrichies
$demandes_pending = enrichir_demandes(get_demandes_by_status($xml, $user_id, 'pending'), $user_emails, 'received');
$demandes_accepted = enrichir_demandes(get_demandes_by_status($xml, $user_id, 'accepted'), $user_emails, 'received');
$demandes_rejected = enrichir_demandes(get_demandes_by_status($xml, $user_id, 'rejected'), $user_emails, 'received');
$demandes_envoyees = enrichir_demandes(get_sent_demandes($xml, $user_id), $user_emails, 'sent');

ob_start();
include(__DIR__ . '/../../template/protected/demandes.template.php');
$content = ob_get_clean();
include __DIR__ . '/sidebar.logic.php';
include __DIR__ . '/../../template/protected/protected.layout.php';