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
        <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center">
            <!-- Attachment button -->
            <label for="file-upload" class="cursor-pointer p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors m-1">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
                </svg>
                <input id="file-upload" type="file" name="file" class="hidden" onchange="updateFileName(this)">
            </label>

            <!-- Text input - REQUIRED -->
            <input type="text" name="text" id="chat-input" placeholder="Tapez votre message..." autocomplete="off"  class="flex-1 px-4 py-3 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 outline-none text-sm" />

            <!-- Emoji button -->
            <button type="button" id="emoji-btn" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors m-1">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Send button - Amber color -->
        <button type="submit" name="send_message" class="w-12 h-12 flex items-center justify-center rounded-full bg-amber-600 hover:bg-amber-700 transition-colors text-white shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </button>
    </form>

    <!-- Modal Emoji Picker -->
    <div id="emoji-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-96 max-w-full mx-4">
            <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Choisir un emoji</h3>
                    <button id="close-emoji-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2 overflow-x-auto">
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200" data-category="smileys">😊</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="people">👍</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="nature">🌱</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="food">🍕</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="travel">✈️</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="objects">⚽</button>
                    <button type="button" class="emoji-category-btn flex-shrink-0 px-3 py-2 text-sm rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400" data-category="symbols">❤️</button>
                </div>
            </div>
            <div class="p-4 h-64 overflow-y-auto">
                <div class="grid grid-cols-8 gap-2" id="emoji-grid">
                    <!-- Emojis will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Emoji data
const emojiData = {
    smileys: ['😀', '😃', '😄', '😁', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫', '😩', '🥺', '😢', '😭', '😤', '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱', '😨', '😰', '😥', '😓', '🤗', '🤔', '🤭', '🤫', '🤥', '😶', '😐', '😑', '😬', '🙄', '😯', '😦', '😧', '😮', '😲', '🥱', '😴', '🤤', '😪', '😵', '🤐', '🥴', '🤢', '🤮', '🤧', '😷', '🤒', '🤕'],
    people: ['👋', '🤚', '🖐️', '✋', '🖖', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👍', '👎', '👊', '✊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💪', '🦾', '🦿', '🦵', '🦶', '👂', '🦻', '👃', '🧠', '🫀', '🫁', '🦷', '🦴', '👀', '👁️', '👅', '👄', '💋', '🩸'],
    nature: ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐽', '🐸', '🐵', '🙈', '🙉', '🙊', '🐒', '🐔', '🐧', '🐦', '🐤', '🐣', '🐥', '🦆', '🦅', '🦉', '🦇', '🐺', '🐗', '🐴', '🦄', '🐝', '🐛', '🦋', '🐌', '🐞', '🐜', '🦟', '🦗', '🕷️', '🕸️', '🦂', '🐢', '🐍', '🦎', '🦖', '🦕', '🐙', '🦑', '🦐', '🦞', '🦀', '🐡', '🐠', '🐟', '🐬', '🐳', '🐋', '🦈', '🐊', '🐅', '🐆', '🦓', '🦍', '🦧', '🐘', '🦛', '🦏', '🐪', '🐫', '🦒', '🦘', '🐃', '🐂', '🐄', '🐎', '🐖', '🐏', '🐑', '🦙', '🐐', '🦌', '🐕', '🐩', '🦮', '🐕‍🦺', '🐈', '🐓', '🦃', '🦚', '🦜', '🦢', '🦩', '🕊️', '🐇', '🦝', '🦨', '🦡', '🦦', '🦥', '🐁', '🐀', '🐿️', '🦔'],
    food: ['🍎', '🍐', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🫐', '🍈', '🍒', '🍑', '🥭', '🍍', '🥥', '🥝', '🍅', '🍆', '🥑', '🥦', '🥬', '🥒', '🌶️', '🫑', '🌽', '🥕', '🫒', '🧄', '🧅', '🥔', '🍠', '🥐', '🥖', '🍞', '🥨', '🥯', '🧀', '🥚', '🍳', '🧈', '🥞', '🧇', '🥓', '🥩', '🍗', '🍖', '🦴', '🌭', '🍔', '🍟', '🍕', '🥪', '🥙', '🧆', '🌮', '🌯', '🫔', '🥗', '🥘', '🫕', '🥫', '🍝', '🍜', '🍲', '🍛', '🍣', '🍱', '🥟', '🦪', '🍤', '🍙', '🍚', '🍘', '🍥', '🍬', '🍫', '🍿', '🍩', '🍪', '🌰', '🥜', '🍯'],
    travel: ['🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒', '🚐', '🛻', '🚚', '🚛', '🚜', '🦯', '🦽', '🦼', '🛴', '🚲', '🛵', '🏍️', '🛺', '🚨', '🚔', '🚍', '🚘', '🚖', '🚡', '🚠', '🚟', '🚃', '🚋', '🚞', '🚝', '🚄', '🚅', '🚈', '🚂', '🚆', '🚇', '🚊', '🚉', '✈️', '🛫', '🛬', '🛩️', '💺', '🛰️', '🚀', '🛸', '🚁', '🛶', '⛵', '🚤', '🛥️', '🛳️', '⛴️', '🚢', '⚓', '⛽', '🚧', '🚦', '🚥', '🗺️', '🗿', '🗽', '🗼', '🏰', '🏯', '🏟️', '🎡', '🎢', '🎠', '⛲', '⛱️', '🏖️', '🏝️', '🏜️', '🌋', '⛰️', '🏔️', '🗻', '🏕️', '⛺', '🏠', '🏡', '🏘️', '🏚️', '🏗️', '🏭', '🏢', '🏬', '🏢', '🏢', '🏢'],
    objects: ['⚽', '🏀', '🏈', '⚾', '🥎', '🎾', '🏐', '🏉', '🥏', '🎱', '🪀', '🏓', '🏸', '🏒', '🏑', '🥍', '🎲', '🎯', '🎳', '🎮', '🎰', '🧩'],
    symbols: ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '☮️', '✝️', '☪️', '🕉️', '☸️', '✡️', '🔯', '🕎', '☯️', '☦️', '🛐', '⛎', '♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐', '♑', '♒', '♓', '🆔', '⚛️', '🉑', '☢️', '☣️', '📴', '📳', '🈶', '🈚', '🈸', '🈺', '🈷️', '✴️', '🆚', '💮', '🉐', '㊙️', '㊗️', '🈴', '🈵', '[ii]', '[ii]', '[ii]', '[ii]', '[ii]', '[ii]', '[ii]', '[ii]', '[ii]', '[ii']
};

let currentCategory = 'smileys';
let emojiPickerVisible = false;

// Initialize emoji picker
function initEmojiPicker() {
    const emojiBtn = document.getElementById('emoji-btn');
    const emojiModal = document.getElementById('emoji-modal');
    const closeModal = document.getElementById('close-emoji-modal');
    const emojiGrid = document.getElementById('emoji-grid');
    const categoryBtns = document.querySelectorAll('.emoji-category-btn');
    const chatInput = document.getElementById('chat-input');

    // Open emoji modal
    emojiBtn.addEventListener('click', function(e) {
        e.preventDefault();
        emojiModal.classList.remove('hidden');
        loadEmojis(currentCategory);
    });

    // Close modal
    closeModal.addEventListener('click', function() {
        emojiModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    emojiModal.addEventListener('click', function(e) {
        if (e.target === emojiModal) {
            emojiModal.classList.add('hidden');
        }
    });

    // Category buttons
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            currentCategory = category;
            
            // Update active category button
            categoryBtns.forEach(b => {
                b.classList.remove('bg-amber-100', 'dark:bg-amber-900', 'text-amber-800', 'dark:text-amber-200');
                b.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
            });
            
            this.classList.add('bg-amber-100', 'dark:bg-amber-900', 'text-amber-800', 'dark:text-amber-200');
            this.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
            
            loadEmojis(category);
        });
    });

    // Load emojis for category
    function loadEmojis(category) {
        const emojis = emojiData[category] || [];
        emojiGrid.innerHTML = '';
        
        emojis.forEach(emoji => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'p-2 text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors';
            button.textContent = emoji;
            
            button.addEventListener('click', function() {
                // Insert emoji at cursor position
                const start = chatInput.selectionStart || 0;
                const end = chatInput.selectionEnd || 0;
                const text = chatInput.value;
                
                chatInput.value = text.substring(0, start) + emoji + text.substring(end);
                chatInput.focus();
                chatInput.setSelectionRange(start + emoji.length, start + emoji.length);
                
                // Close modal
                emojiModal.classList.add('hidden');
            });
            
            emojiGrid.appendChild(button);
        });
    }

    // Initial load
    loadEmojis(currentCategory);
}

// Initialize emoji picker when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initEmojiPicker();
});

// Also initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEmojiPicker);
} else {
    initEmojiPicker();
}

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
    
    // Nettoyer le champ après envoi si besoin
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

// Validation du formulaire
document.getElementById('chat-form').addEventListener('submit', function(e) {
    const textInput = document.getElementById('chat-input');
    const fileInput = document.getElementById('file-upload');
    
    // Vérifier si le message est vide ET qu'il n'y a pas de fichier
    if (textInput.value.trim() === '' && !fileInput.files.length) {
        e.preventDefault();
        textInput.focus();
        
        // Ajouter un effet visuel pour indiquer que le champ est requis
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
</script>