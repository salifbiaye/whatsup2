<div class="mx-auto bg-white h-screen dark:bg-gray-900 flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <img src="/whatsup/<?php echo $contactAvatar!='' ? $contactAvatar : 'storage/avatars/avatar_default.png'; ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
        <div class="flex-1">
            <div class="font-medium text-gray-900 dark:text-gray-100 text-sm"><?php echo $contactDisplayName; ?></div>
            <div class="text-xs text-gray-500 dark:text-gray-400" id="online-status">
                <?php 
                // Vérifier si le contact est en ligne
                $status = 'Hors ligne';
                if (isset($contactStatus) && $contactStatus === 'online') {
                    $status = 'En ligne';
                }
                echo htmlspecialchars($status);
                ?>
            </div>
        </div>
   
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto px-4 py-2 bg-[url('/whatsup/assets/logo/bg-light-chat.png')] dark:bg-[url('/whatsup/assets/logo/bg-dark-chat.png')] dark:bg-gray-800" id="messages-box">
        <!-- Loading indicator -->
        <div id="loading-indicator" class="flex items-center justify-center h-full">
            <div class="text-center">
                <div class="w-8 h-8 border-4 border-gray-200 border-t-amber-500 rounded-full animate-spin mx-auto mb-2"></div>
                <div class="text-gray-500 dark:text-gray-400 text-sm">Chargement des messages...</div>
            </div>
        </div>
        
        <!-- Empty state -->
        <div id="empty-state" class="flex items-center justify-center h-full hidden">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="text-gray-500 dark:text-gray-400 text-sm">Aucun message pour le moment</div>
                <div class="text-gray-400 dark:text-gray-500 text-xs mt-1">Commencez la conversation</div>
            </div>
        </div>
        
        <!-- Messages will be loaded here -->
        <div id="messages-container" class="space-y-2 py-2 hidden"></div>
    </div>

    <!-- Input Area -->
    <?php include __DIR__ . '/form.php'; ?>
</div>

<script>
// Configuration globale
window.contactId = '<?php echo $contact_id; ?>';
window.currentUserId = '<?php echo $_SESSION['email_id']; ?>';



// Fonction d'échappement HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Gestion des événements de base
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

// Événements DOM
document.addEventListener('DOMContentLoaded', function() {
    // Remove file handler
    const removeFileBtn = document.getElementById('remove-file');
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function() {
            document.getElementById('file-upload').value = '';
            document.getElementById('file-name-preview').classList.add('hidden');
        });
    }

    // Submit on Enter
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const submitBtn = document.querySelector('#chat-form button[type=submit]');
                if (submitBtn) submitBtn.click();
            }
        });

        // Auto-resize textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Gestion de l'envoi de formulaire
    const chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            // Marquer pour scroller après rechargement
            sessionStorage.setItem('chatScrollToBottom', '1');
        });
    }
});
</script>

<!-- Chargement du système de polling -->
<script src="/whatsup/components/chat_private/poll.js"></script>