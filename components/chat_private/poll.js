function loadMessages() {
    var box = document.getElementById('messages-box');
    if (!box) return;
    
    // Vérifier si on était tout en bas avant la mise à jour
    var wasAtBottom = Math.abs(box.scrollTop + box.clientHeight - box.scrollHeight) < 10;
    
    // Sauvegarder la position actuelle
    var prevScroll = box.scrollTop;
    var prevHeight = box.scrollHeight;
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/whatsup/logic/protected/chat_private_poll.logic.php?user=' + encodeURIComponent(window.contactId), true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Comparer le contenu pour éviter les mises à jour inutiles
            var currentContent = box.innerHTML;
            var newContent = xhr.responseText;
            
            // Si le contenu n'a pas changé, ne pas mettre à jour
            if (currentContent === newContent) {
                return;
            }
            
            // Mettre à jour le contenu
            box.innerHTML = newContent;
            
            // Gérer le scroll après mise à jour
            if (wasAtBottom) {
                // Si l'utilisateur était en bas, rester en bas
                box.scrollTop = box.scrollHeight;
            } else {
                // Sinon, essayer de maintenir la position relative
                var newHeight = box.scrollHeight;
                var heightDiff = newHeight - prevHeight;
                
                if (heightDiff > 0) {
                    // Ajuster la position pour compenser les nouveaux messages
                    box.scrollTop = prevScroll + heightDiff;
                } else {
                    // Garder la position si pas de nouveau contenu
                    box.scrollTop = prevScroll;
                }
            }
        }
    };
    
    xhr.onerror = function() {
        console.error('Erreur lors du chargement des messages');
    };
    
    xhr.send();
}

// Fonction pour scroll vers le bas
function scrollToBottom() {
    var box = document.getElementById('messages-box');
    if (box) {
        box.scrollTop = box.scrollHeight;
    }
}

// Fonction pour vérifier si l'utilisateur est en bas
function isAtBottom() {
    var box = document.getElementById('messages-box');
    if (!box) return false;
    return Math.abs(box.scrollTop + box.clientHeight - box.scrollHeight) < 10;
}

// Exposer les fonctions globalement
window.loadMessages = loadMessages;
window.scrollToBottom = scrollToBottom;
window.isAtBottom = isAtBottom;

// Initialisation au chargement de la page
window.addEventListener('DOMContentLoaded', function() {
    if (window.contactId) {
        // Charger les messages une première fois
        loadMessages();
        
        // Démarrer le polling toutes les 2 secondes
        setInterval(loadMessages, 2000);
    }
    
    // Scroll automatique après reload si flag présent
    var box = document.getElementById('messages-box');
    if (sessionStorage.getItem('chatScrollToBottom') && box) {
        scrollToBottom();
        sessionStorage.removeItem('chatScrollToBottom');
    }
    
    // Gérer l'envoi de messages
    var chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            // Marquer qu'on doit scroller en bas après le rechargement
            sessionStorage.setItem('chatScrollToBottom', '1');
            
            // Scroll immédiat pour un feedback visuel
            setTimeout(function() {
                scrollToBottom();
            }, 100);
        });
    }
    
    // Scroll initial vers le bas
    setTimeout(scrollToBottom, 100);
});