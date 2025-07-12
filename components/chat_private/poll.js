function loadMessages() {
    var box = document.getElementById('messages-box');
    if (!box) return;
    // On vérifie si on était tout en bas
    var wasAtBottom = Math.abs(box.scrollTop + box.clientHeight - box.scrollHeight) < 10;
    var prevScroll = box.scrollTop;
    var prevHeight = box.scrollHeight;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/whatsup2/logic/protected/chat_private_poll.logic.php?user=' + encodeURIComponent(window.contactId), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var oldHeight = box.scrollHeight;
            box.innerHTML = xhr.responseText;
            var newHeight = box.scrollHeight;
            if (wasAtBottom) {
                box.scrollTop = box.scrollHeight;
            } else {
                // Essaie de garder la position relative
                box.scrollTop = prevScroll + (newHeight - prevHeight);
            }
        }
    };
    xhr.send();
}
window.loadMessages = loadMessages;
window.addEventListener('DOMContentLoaded', function() {
    if (window.contactId) {
        loadMessages();
        setInterval(loadMessages, 2000);
    }
    // Scroll auto après reload si flag
    var box = document.getElementById('messages-box');
    if (sessionStorage.getItem('chatScrollToBottom') && box) {
        box.scrollTop = box.scrollHeight;
        sessionStorage.removeItem('chatScrollToBottom');
    }
    // Scroll en bas après envoi de message
    var chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', function() {
            sessionStorage.setItem('chatScrollToBottom', '1');
            setTimeout(function() {
                var box = document.getElementById('messages-box');
                if (box) box.scrollTop = box.scrollHeight;
            }, 100);
        });
    }
});
