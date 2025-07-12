<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['email_id'])) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        exit;
    }

    // Récupérer l'ID de l'utilisateur
    $userId = $_SESSION['email_id'];

    // Récupérer les données du formulaire
    $displayName = $_POST['display_name'] ?? '';
    $avatar = $_FILES['avatar'] ?? null;

    // Vérifier si un nouvel avatar a été téléchargé
    $newAvatar = '';
    if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../storage/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = 'avatar_' . $userId . '_' . time() . '_' . basename($avatar['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($avatar['tmp_name'], $targetPath)) {
            $newAvatar = 'storage/avatars/' . $fileName;
        }
    }

    try {
        // Charger le fichier XML
        $users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
        
        // Trouver l'utilisateur à mettre à jour
        foreach ($users->user as $user) {
            if ((string)$user['id'] === $userId) {
                // Mettre à jour le nom d'affichage
                $user->displayName = $displayName;
                
                // Mettre à jour l'avatar si un nouveau fichier a été téléchargé
                if ($newAvatar) {
                    $user->avatar = $newAvatar;
                }
                
                // Sauvegarder les modifications
                $xmlString = $users->asXML();
                file_put_contents(__DIR__ . '/../../storage/xml/users.xml', $xmlString);
                
                // Mettre à jour la session
                $_SESSION['user']['displayName'] = $displayName;
                if ($newAvatar) {
                    $_SESSION['user']['avatar'] = $newAvatar;
                }
                
                // Retourner une réponse JSON de succès
                echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès']);
                exit;
            }
        }
    } catch (Exception $e) {
        // Retourner une réponse JSON d'erreur
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
