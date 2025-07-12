<?php
/**
 * Logique de recherche des contacts
 * @param string $searchTerm Le terme de recherche
 * @return array Tableau d'objets contact correspondant à la recherche
 */
function searchContacts($searchTerm) {
    $xmlFile = __DIR__ . '/../storage/xml/users.xml';
    if (!file_exists($xmlFile)) {
        return [];
    }

    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        return [];
    }

    $results = [];
    $searchTerm = strtolower($searchTerm);

    foreach ($xml->user as $user) {
        $displayName = (string)$user->displayName;
        $email = (string)$user->email;
        
        // Recherche dans le displayName et l'email
        if (stripos($displayName, $searchTerm) !== false || 
            stripos($email, $searchTerm) !== false) {
            
            $results[] = [
                'id' => (string)$user['id'],
                'displayName' => $displayName,
                'email' => $email,
                'avatar' => (string)$user->avatar,
                'status' => (string)$user->status
            ];
        }
    }

    return $results;
}

// Endpoint pour la recherche AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $results = searchContacts($_POST['search']);
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
