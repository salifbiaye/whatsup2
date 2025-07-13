<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['email_id']; // ou autre identifiant

$xml_file = __DIR__ . '/../../storage/xml/demandes.xml';
$xml = simplexml_load_file($xml_file);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sender_id'], $_POST['action'])) {
    $sender_id = $_POST['sender_id'];
    $action = $_POST['action']; // 'accept' ou 'reject'

    foreach ($xml->demande as $demande) {
        if ((string)$demande->sender_id === $sender_id && (string)$demande->receiver_id === (string)$user_id && (string)$demande->status === 'pending') {
            $demande->status = ($action === 'accept') ? 'accepted' : 'rejected';
            break;
        }
    }
    $xml->asXML($xml_file);
    if ($action === 'accept') {
        // Charger users.xml
        $users_file = __DIR__ . '/../../storage/xml/users.xml';
        $users_xml = simplexml_load_file($users_file);
    
        // Trouver le receiver (celui qui accepte)
        foreach ($users_xml->user as $user) {
            if ((string)$user['id'] === (string)$user_id) {
                if (!isset($user->contacts)) $user->addChild('contacts');
                // Vérifier si le contact existe déjà
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
    header('Location: /whatsup2/demandes');
    exit;
}

// Préparation des listes
$demandes_pending = [];
$demandes_accepted = [];
$demandes_rejected = [];

foreach ($xml->demande as $demande) {
    if ((string)$demande->receiver_id === (string)$user_id) {
        switch ((string)$demande->status) {
            case 'pending':
                $demandes_pending[] = $demande;
                break;
            case 'accepted':
                $demandes_accepted[] = $demande;
                break;
            case 'rejected':
                $demandes_rejected[] = $demande;
                break;
        }
    }
}
$users_xml = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$user_emails = [];
foreach ($users_xml->user as $user) {
    $user_emails[(string)$user['id']] = (string)$user->email;
}

function format_date_humaine($iso_date) {
    $dt = new DateTime($iso_date);
    // Format : 13 juillet 2025, 16:58
    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR');
    return strftime('%e %B %Y, %H:%M', $dt->getTimestamp());
}

// Mapper chaque demande avec email et date lisible
function enrichir_demandes($demandes, $user_emails) {
    $result = [];
    foreach ($demandes as $demande) {
        $id = (string)$demande->sender_id;
        $result[] = [
            'email' => $user_emails[$id] ?? $id,
            'date' => format_date_humaine((string)$demande->date),
            'raw'  => $demande
        ];
    }
    return $result;
}
$demandes_pending = enrichir_demandes($demandes_pending, $user_emails);
$demandes_accepted = enrichir_demandes($demandes_accepted, $user_emails);
$demandes_rejected = enrichir_demandes($demandes_rejected, $user_emails);

// Préparer la liste des demandes envoyées par l'utilisateur courant
$current_user_id = $_SESSION['email_id'];
$demandes_envoyees = [];
if (isset($xml)) {
    foreach ($xml->demande as $demande) {
        if ((string)$demande->sender_id === $current_user_id) {
            $demandes_envoyees[] = [
                'email' => $user_emails[(string)$demande->receiver_id] ?? $demande->receiver_id,
                'date' => format_date_humaine((string)$demande->date),
                'status' => (string)$demande->status,
                'raw'   => $demande
            ];
        }
    }
}

ob_start();
include(__DIR__ . '/../../template/protected/demandes.template.php');
$content = ob_get_clean();
include __DIR__ . '/sidebar.logic.php';
include __DIR__ . '/../../template/protected/protected.layout.php';