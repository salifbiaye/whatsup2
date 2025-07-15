// Variables globales pour le cache
let lastMessagesHash = null;
let currentMessages = [];
let isLoading = false;

// Fonction pour générer le HTML d'un message
function generateMessageHTML(msg) {
    const isOwnMessage = msg.isOwnMessage;
    const justifyClass = isOwnMessage ? 'justify-end' : 'justify-start';
    const bgClass = isOwnMessage ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100';
    const timeClass = isOwnMessage ? 'text-amber-100' : 'text-gray-500 dark:text-gray-400';
    const tailClass = isOwnMessage ? 'absolute -right-1 bottom-0' : 'absolute -left-1 bottom-0';
    const tailColor = isOwnMessage ? 'text-amber-500' : 'text-white dark:text-gray-800';
    const tailPath = isOwnMessage ? 'M0 0v12l12-12H0z' : 'M12 0v12L0 0h12z';
    
    // Nom de l'expéditeur (seulement pour les autres)
    const senderNameHTML = !isOwnMessage ? `
        <div class="text-xs font-semibold text-amber-600 dark:text-amber-400 mb-1">
            ${escapeHtml(msg.senderName)}
        </div>
    ` : '';
    
    // Fichier joint
    const fileHTML = msg.file ? `
        <div class="mt-2 p-2 ${isOwnMessage ? 'bg-amber-600/30' : 'bg-gray-100 dark:bg-gray-700'} rounded">
            <a class="flex items-center gap-2 text-xs ${isOwnMessage ? 'text-amber-100 hover:text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100'} transition-colors" 
               href="/whatsup/${escapeHtml(msg.file.path.replace('storage/', '/storage/'))}" target="_blank">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
                </svg>
                <span class="truncate">${escapeHtml(msg.file.name)}</span>
            </a>
        </div>
    ` : '';
    
    // Icône de statut pour les messages envoyés
    const statusIcon = isOwnMessage ? `
        <div class="ml-2">
            <svg class="w-4 h-4 text-amber-100" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
    ` : '';
    
    // Formatage du timestamp
    const time = new Date(msg.timestamp).toLocaleString('fr-FR', { 
        hour: '2-digit', 
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
    return `
        <div class="flex ${justifyClass}" data-message-id="${escapeHtml(msg.id)}">
            <div class="max-w-xs sm:max-w-md lg:max-w-lg xl:max-w-xl">
                <div class="relative group">
                    <div class="${bgClass} px-3 py-2 rounded-lg shadow-sm">
                        ${senderNameHTML}
                        
                        <!-- Message content -->
                        <div class="text-sm p-4 leading-relaxed whitespace-normal break-words">
                            ${escapeHtml(msg.text).replace(/\n/g, '<br>')}
                        </div>
                        
                        ${fileHTML}
                        
                        <!-- Message info -->
                        <div class="flex items-center justify-between mt-1">
                            <div class="text-xs ${timeClass}">
                                ${time}
                            </div>
                            ${statusIcon}
                        </div>
                    </div>
                    
                    <!-- Message tail -->
                    <div class="${tailClass}">
                        <svg class="w-3 h-3 ${tailColor}" viewBox="0 0 12 12" fill="currentColor">
                            <path d="${tailPath}"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fonction pour générer le HTML de l'état vide
function generateEmptyStateHTML() {
    return `
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121L17 20zM9 12a4 4 0 100-8 4 4 0 000 8zM11 14a6 6 0 00-6 6v2h12v-2a6 6 0 00-6-6z"></path>
                    </svg>
                </div>
                <div class="text-gray-500 dark:text-gray-400 text-sm">Aucun message dans ce groupe</div>
                <div class="text-gray-400 dark:text-gray-500 text-xs mt-1">Commencez la conversation</div>
            </div>
        </div>
    `;
}

// Fonction utilitaire pour échapper le HTML
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

// Fonction pour calculer un hash simple des messages
function calculateMessagesHash(messages) {
    if (!messages || messages.length === 0) return 'empty';
    return messages.map(msg => `${msg.id}-${msg.timestamp}`).join('|');
}

// Fonction pour comparer et mettre à jour les messages
function updateMessagesDisplay(messages) {
    const messagesBox = document.getElementById('messages-box');
    if (!messagesBox) return;
    
    // Calculer le hash des nouveaux messages
    const newHash = calculateMessagesHash(messages);
    
    // Debugging
    console.log('Hash précédent:', lastMessagesHash);
    console.log('Nouveau hash:', newHash);
    console.log('Nombre de messages:', messages.length);
    
    // Si rien n'a changé, ne pas mettre à jour
    if (newHash === lastMessagesHash) {
        console.log('Aucun changement détecté, pas de mise à jour');
        return;
    }
    
    // Vérifier si on était tout en bas avant la mise à jour
    const wasAtBottom = isAtBottom();
    
    // Générer le nouveau HTML
    let newHTML;
    if (messages.length === 0) {
        newHTML = generateEmptyStateHTML();
    } else {
        const messagesHTML = messages.map(msg => generateMessageHTML(msg)).join('');
        newHTML = `<div class="space-y-2 py-2">${messagesHTML}</div>`;
    }
    
    // Mettre à jour le contenu
    messagesBox.innerHTML = newHTML;
    
    // Gérer le scroll
    if (wasAtBottom || lastMessagesHash === null) {
        // Si l'utilisateur était en bas ou c'est le premier chargement, rester en bas
        scrollToBottom();
    }
    
    // Mettre à jour le cache
    lastMessagesHash = newHash;
    currentMessages = messages;
    
    console.log('Messages mis à jour avec succès');
}

// Fonction principale pour charger les messages
function loadGroupMessages() {
    if (!window.groupId) {
        console.error('groupId non défini');
        return;
    }
    
    if (isLoading) {
        console.log('Chargement déjà en cours, ignorer');
        return;
    }
    
    isLoading = true;
    
    const xhr = new XMLHttpRequest();
    // Changez ce chemin selon votre structure de fichiers
    xhr.open('GET', `/whatsup/logic/protected/chat_group_poll.logic.php?group=${encodeURIComponent(window.groupId)}`, true);
    
    xhr.onload = function() {
        isLoading = false;
        
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    console.log('Messages chargés:', response.messages.length);
                    updateMessagesDisplay(response.messages);
                } else {
                    console.error('Erreur serveur:', response.error);
                }
            } catch (e) {
                console.error('Erreur lors du parsing JSON:', e);
            }
        } else {
            console.error('Erreur HTTP:', xhr.status);
        }
    };
    
    xhr.onerror = function() {
        isLoading = false;
        console.error('Erreur lors du chargement des messages du groupe');
    };
    
    xhr.send();
}

// Fonction pour scroll vers le bas
function scrollToBottom() {
    const box = document.getElementById('messages-box');
    if (box) {
        box.scrollTop = box.scrollHeight;
    }
}

// Fonction pour vérifier si l'utilisateur est en bas
function isAtBottom() {
    const box = document.getElementById('messages-box');
    if (!box) return false;
    return Math.abs(box.scrollTop + box.clientHeight - box.scrollHeight) < 10;
}

// Fonction pour scroller vers le bas avec animation douce
function smoothScrollToBottom() {
    const box = document.getElementById('messages-box');
    if (box) {
        box.scrollTo({
            top: box.scrollHeight,
            behavior: 'smooth'
        });
    }
}

// Exposer les fonctions globalement
window.loadGroupMessages = loadGroupMessages;
window.scrollToBottom = scrollToBottom;
window.smoothScrollToBottom = smoothScrollToBottom;
window.isAtBottom = isAtBottom;

// Initialisation au chargement de la page
window.addEventListener('DOMContentLoaded', function() {
    if (window.groupId) {
        console.log('Initialisation du chat pour le groupe:', window.groupId);
        
        // Charger les messages une première fois
        loadGroupMessages();
        
        // Démarrer le polling toutes les 3 secondes (augmenté pour éviter surcharge)
        setInterval(loadGroupMessages, 3000);
    }
    
    // Scroll automatique après reload si flag présent
    if (sessionStorage.getItem('groupChatScrollToBottom')) {
        setTimeout(() => {
            scrollToBottom();
            sessionStorage.removeItem('groupChatScrollToBottom');
        }, 100);
    }
    
    // Gérer l'envoi de messages
    const chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            // Marquer qu'on doit scroller en bas après le rechargement
            sessionStorage.setItem('groupChatScrollToBottom', '1');
            
            // Scroll immédiat pour un feedback visuel
            setTimeout(scrollToBottom, 100);
        });
    }
    
    // Scroll initial vers le bas
    setTimeout(scrollToBottom, 100);
});

// Gestion des notifications visuelles pour nouveaux messages
function showNewMessageNotification() {
    // Effet visuel subtil pour indiquer de nouveaux messages
    const box = document.getElementById('messages-box');
    if (box && !isAtBottom()) {
        // Ajouter une classe pour l'effet visuel
        box.classList.add('new-messages-available');
        
        // Retirer la classe après quelques secondes
        setTimeout(() => {
            box.classList.remove('new-messages-available');
        }, 3000);
    }
}