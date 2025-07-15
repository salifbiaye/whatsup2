<?php
// Variables attendues : $group (SimpleXMLElement), $messages, $members, $userId
?>
<div class="mx-auto bg-white h-screen dark:bg-gray-900 flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <img src="/whatsup/storage/avatars/avatar_group.png" alt="Avatar Groupe" class="w-10 h-10 rounded-full object-cover">
        <div class="flex-1">
            <div class="font-medium text-gray-900 dark:text-gray-100 text-sm"><?php echo htmlspecialchars((string)$group->name); ?><span class="text-xs text-gray-500 dark:text-gray-400"> <?php echo count($members); ?> membres</span></div>
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                <?php
                // Récupérer les informations des membres avec troncage
                $memberInfo = get_group_member_names($group, $users_xml);
                
                // Afficher l'admin
                echo '<span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-2 py-1 text-sm font-medium">' . htmlspecialchars($memberInfo['admin']['name']) . '</span>';
                
                // Afficher les 3 autres membres
                foreach ($memberInfo['other_members'] as $member) {
                    echo '<span class="inline-block bg-gray-100 dark:bg-gray-600 rounded-full px-2 py-1 text-sm font-medium mx-1">' . htmlspecialchars($member['name']) . '</span>';
                }
                
                // Si il y a d'autres membres, afficher "et X autres"
                if ($memberInfo['others_count'] > 0) {
                    echo '<span class="inline-block bg-gray-100 dark:bg-gray-600 rounded-full px-2 py-1 text-sm font-medium mx-1">et ' . $memberInfo['others_count'] . ' autres</span>';
                }
                ?>
            </div>
        </div>
   
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto px-4 py-2 bg-[url('/whatsup/assets/logo/bg-light-chat.png')] dark:bg-[url('/whatsup/assets/logo/bg-dark-chat.png')] dark:bg-gray-800 relative" id="messages-box">
        <!-- Le contenu sera généré par JavaScript -->
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <div class="w-8 h-8 mx-auto mb-4 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                <div class="text-gray-500 dark:text-gray-400 text-sm">Chargement des messages...</div>
            </div>
        </div>
    </div>

    <!-- Input Area -->
    <?php include __DIR__ . '/form.php'; ?>
</div>

<!-- CSS pour les effets visuels -->
<style>
.new-messages-available {
    position: relative;
}

.new-messages-available::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #f59e0b, #d97706);
    animation: pulse 2s infinite;
    z-index: 10;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Améliorer le scroll personnalisé */
#messages-box::-webkit-scrollbar {
    width: 6px;
}

#messages-box::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.1);
    border-radius: 3px;
}

#messages-box::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.3);
    border-radius: 3px;
}

#messages-box::-webkit-scrollbar-thumb:hover {
    background: rgba(0,0,0,0.5);
}

/* Animation pour les nouveaux messages */
.message-animation {
    animation: messageSlideIn 0.3s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Définir l'ID du groupe pour JavaScript
window.groupId = <?php echo json_encode($group_id); ?>;

// Fonctions utilitaires pour le formulaire
function updateFileName(input) {
    const preview = document.getElementById('file-name-preview');
    const fileName = document.getElementById('file-name');
    const removeBtn = document.getElementById('remove-file');
    
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Gestionnaire pour supprimer le fichier
document.addEventListener('DOMContentLoaded', function() {
    const removeFileBtn = document.getElementById('remove-file');
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('file-upload');
            const preview = document.getElementById('file-name-preview');
            
            if (fileInput) fileInput.value = '';
            if (preview) preview.classList.add('hidden');
        });
    }
    
    // Soumission avec Entrée
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const submitBtn = document.querySelector('#chat-form button[type=submit]');
                if (submitBtn) submitBtn.click();
            }
        });
        
        // Auto-resize du textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }
});
</script>

<!-- Inclure le script de polling -->
<script src="/whatsup/components/chat_group/poll.js"></script>