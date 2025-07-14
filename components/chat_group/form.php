<div class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-4 py-3">
    <!-- File preview -->
    <div id="file-name-preview" class="mb-2 hidden">
        <div class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
            </svg>
            <span class="text-sm text-gray-700 dark:text-gray-300 flex-1 truncate" id="file-name"></span>
            <button type="button" id="remove-file" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Input form -->
    <form method="post" enctype="multipart/form-data" class="flex items-end gap-2" id="chat-form">
        <input type="hidden" name="send_group_message" value="1">
        <input type="hidden" name="group_id" value="<?php echo htmlspecialchars($group['id']); ?>">
        
        <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center">
            <!-- Attachment button -->
            <label for="file-upload" class="cursor-pointer p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors m-1">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
                </svg>
                <input id="file-upload" type="file" name="file" class="hidden" onchange="updateFileName(this)">
            </label>

            <!-- Text input -->
            <input type="text" name="message" id="chat-input" placeholder="Votre message..." autocomplete="off" class="flex-1 px-4 py-3 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 outline-none text-sm" />

            <!-- Emoji button -->
            <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors m-1">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Send button - Amber color -->
        <button type="submit" name="send_group_message" class="w-12 h-12 flex items-center justify-center rounded-full bg-amber-600 hover:bg-amber-700 transition-colors text-white shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </button>
    </form>
</div>

<script>
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

// Remove file handler
document.getElementById('remove-file').addEventListener('click', function() {
    document.getElementById('file-upload').value = '';
    document.getElementById('file-name-preview').classList.add('hidden');
});

// Submit on Enter
document.getElementById('chat-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.querySelector('#chat-form button[type=submit]').click();
    }
});

// Form validation
document.getElementById('chat-form').addEventListener('submit', function(e) {
    const textInput = document.getElementById('chat-input');
    const fileInput = document.getElementById('file-upload');
    
    // Vérifier si les deux champs sont vides
    if (textInput.value.trim() === '' && !fileInput.files.length) {
        e.preventDefault();
        textInput.focus();
        
        // Ajouter un effet visuel pour indiquer qu'au moins un champ est requis
        textInput.classList.add('ring-2', 'ring-red-500');
        setTimeout(() => {
            textInput.classList.remove('ring-2', 'ring-red-500');
        }, 2000);
        
        return false;
    }
    
    // Nettoyer le champ après envoi
    setTimeout(() => {
        textInput.value = '';
        textInput.style.height = 'auto';
        scrollToBottom();
    }, 100);
});

// Auto-scroll to bottom
function scrollToBottom() {
    const messagesBox = document.getElementById('messages-box');
    if (messagesBox) {
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }
}

// Scroll to bottom on page load
window.addEventListener('load', scrollToBottom);

// Auto-resize textarea on input
document.getElementById('chat-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});
</script>