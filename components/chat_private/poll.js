// Système de polling pour les messages
class ChatPoller {
    constructor() {
        this.lastMessageId = 0;
        this.isLoading = false;
        this.pollInterval = null;
        this.messagesContainer = null;
        this.messagesBox = null;
        this.loadingIndicator = null;
        this.emptyState = null;
        this.isInitialized = false;
    }

    init() {
        // Attendre que le DOM soit prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initialize());
        } else {
            this.initialize();
        }
    }

    initialize() {
        this.messagesContainer = document.getElementById('messages-container');
        this.messagesBox = document.getElementById('messages-box');
        this.loadingIndicator = document.getElementById('loading-indicator');
        this.emptyState = document.getElementById('empty-state');

        if (!this.messagesContainer || !this.messagesBox) {
            console.error('Éléments requis non trouvés');
            return;
        }

        // Vérifier si on doit scroller après rechargement
        if (sessionStorage.getItem('chatScrollToBottom')) {
            sessionStorage.removeItem('chatScrollToBottom');
            setTimeout(() => this.scrollToBottom(), 100);
        }

        // Charger les messages initiaux
        this.loadMessages(true);
        
        // Démarrer le polling
        this.startPolling();
        
        this.isInitialized = true;
    }

    async loadMessages(isInitialLoad = false) {
        if (this.isLoading || !window.contactId) return;

        this.isLoading = true;

        try {
            const url = `/whatsup/logic/protected/chat_private_poll.logic.php?user=${encodeURIComponent(window.contactId)}&last_id=${this.lastMessageId}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (isInitialLoad) {
                this.handleInitialLoad(data);
            } else if (data.has_new) {
                this.handleNewMessages(data);
            }

        } catch (error) {
            console.error('Erreur lors du chargement des messages:', error);
            this.showError();
        } finally {
            this.isLoading = false;
        }
    }

    handleInitialLoad(data) {
        this.hideLoading();

        if (data.messages.length === 0) {
            this.showEmptyState();
            return;
        }

        this.showMessagesContainer();
        this.renderMessages(data.messages);
        this.lastMessageId = data.total_count;
        this.scrollToBottom();
    }

    handleNewMessages(data) {
        const wasAtBottom = this.isAtBottom();
        
        // Ajouter les nouveaux messages
        data.messages.forEach(message => {
            this.appendMessage(message);
        });

        // Mettre à jour l'ID du dernier message
        this.lastMessageId = data.total_count;

        // Scroller si l'utilisateur était en bas
        if (wasAtBottom) {
            this.scrollToBottom();
        }
    }

    renderMessages(messages) {
        this.messagesContainer.innerHTML = '';
        messages.forEach(message => {
            this.appendMessage(message);
        });
    }

    appendMessage(message) {
        const messageHtml = this.createMessageHTML(message);
        this.messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
    }

    createMessageHTML(msg) {
        const isOwn = msg.is_own;
        const justifyClass = isOwn ? 'justify-end' : 'justify-start';
        const bgClass = isOwn ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100';
        const timeClass = isOwn ? 'text-amber-100' : 'text-gray-500 dark:text-gray-400';
        const tailClass = isOwn ? 'absolute -right-1 bottom-0' : 'absolute -left-1 bottom-0';
        const tailColorClass = isOwn ? 'text-amber-500' : 'text-white dark:text-gray-800';
        const tailPath = isOwn ? 'M0 0v12l12-12H0z' : 'M12 0v12L0 0h12z';
        
        // Formatage du temps
        const timestamp = new Date(msg.timestamp);
        const timeString = timestamp.toLocaleString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' ,
            day: '2-digit',
            month: '2-digit',
            year: '2-digit'
        });
        
        // Contenu du fichier
        let fileContent = '';
        if (msg.file) {
            const fileColorClass = isOwn ? 'bg-amber-600/30' : 'bg-gray-100 dark:bg-gray-700';
            const fileLinkClass = isOwn ? 'text-amber-100 hover:text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100';
            const filePath = msg.file.path.replace('storage/', '/storage/');
            
            fileContent = `
                <div class="mt-2 p-2 ${fileColorClass} rounded">
                    <a class="flex items-center gap-2 text-xs ${fileLinkClass} transition-colors" href="/whatsup/${filePath}" target="_blank">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
                        </svg>
                        <span class="truncate">${this.escapeHtml(msg.file.name)}</span>
                    </a>
                </div>`;
        }
        
        // Indicateur de message envoyé
        const sentIndicator = isOwn ? `
            <div class="ml-2">
                <svg class="w-4 h-4 text-amber-100" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>` : '';
        
        return `
            <div class="flex ${justifyClass}" data-message-id="${msg.id}">
                <div class="max-w-xs sm:max-w-md lg:max-w-lg xl:max-w-xl">
                    <div class="relative group">
                        <div class="${bgClass}   px-3 py-2 rounded-lg shadow-sm">
                            <div class="text-sm p-4 leading-relaxed whitespace-normal  break-words">
                                ${this.escapeHtml(msg.text).replace(/\n/g, '<br>')}
                            </div>
                            ${fileContent}
                            <div class="flex items-center justify-between mt-1">
                                <div class="text-xs ${timeClass}">
                                    ${timeString}
                                </div>
                                ${sentIndicator}
                            </div>
                        </div>
                        <div class="${tailClass}">
                            <svg class="w-3 h-3 ${tailColorClass}" viewBox="0 0 12 12" fill="currentColor">
                                <path d="${tailPath}"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Gestion des états d'affichage
    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.classList.add('hidden');
        }
    }

    showEmptyState() {
        if (this.emptyState) {
            this.emptyState.classList.remove('hidden');
        }
        if (this.messagesContainer) {
            this.messagesContainer.classList.add('hidden');
        }
    }

    showMessagesContainer() {
        if (this.messagesContainer) {
            this.messagesContainer.classList.remove('hidden');
        }
        if (this.emptyState) {
            this.emptyState.classList.add('hidden');
        }
    }

    showError() {
        // Afficher un message d'erreur discret
        console.error('Erreur de connexion au serveur');
    }

    // Gestion du scroll
    scrollToBottom() {
        if (this.messagesBox) {
            this.messagesBox.scrollTop = this.messagesBox.scrollHeight;
        }
    }

    isAtBottom() {
        if (!this.messagesBox) return false;
        return Math.abs(this.messagesBox.scrollTop + this.messagesBox.clientHeight - this.messagesBox.scrollHeight) < 10;
    }

    // Gestion du polling
    startPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
        
        this.pollInterval = setInterval(() => {
            this.loadMessages(false);
        }, 2000);
    }

    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    }

    // Méthodes publiques
    refresh() {
        this.lastMessageId = 0;
        this.loadMessages(true);
    }

    sendMessage() {
        // Marquer pour scroller après rechargement
        sessionStorage.setItem('chatScrollToBottom', '1');
        
        // Scroll immédiat pour un feedback visuel
        setTimeout(() => {
            this.scrollToBottom();
        }, 100);
    }
}

// Initialisation globale
const chatPoller = new ChatPoller();

// Fonctions globales pour compatibilité
window.loadMessages = () => chatPoller.loadMessages(false);
window.scrollToBottom = () => chatPoller.scrollToBottom();
window.isAtBottom = () => chatPoller.isAtBottom();
window.refreshChat = () => chatPoller.refresh();

// Gestion des événements de visibilité de la page
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        chatPoller.stopPolling();
    } else {
        chatPoller.startPolling();
    }
});

// Gestion du beforeunload
window.addEventListener('beforeunload', function() {
    chatPoller.stopPolling();
});

// Initialisation
chatPoller.init();