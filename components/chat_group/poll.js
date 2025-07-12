function loadMessages() {
    var box = document.getElementById('messages-box');
    if (!box) return;
    var wasAtBottom = Math.abs(box.scrollTop + box.clientHeight - box.scrollHeight) < 10;
    var prevScroll = box.scrollTop;
    var prevHeight = box.scrollHeight;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/whatsup2/logic/protected/chat_group_poll.logic.php?group=' + encodeURIComponent(window.groupId), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            box.innerHTML = xhr.responseText;
            var newHeight = box.scrollHeight;
            if (wasAtBottom) {
                box.scrollTop = box.scrollHeight;
            } else {
                box.scrollTop = prevScroll + (newHeight - prevHeight);
            }
        }
    };
    xhr.send();
}
window.loadMessages = loadMessages;
window.addEventListener('DOMContentLoaded', function() {
    if (window.groupId) {
        loadMessages();
        setInterval(loadMessages, 2000);
    }
    var box = document.getElementById('messages-box');
    if (sessionStorage.getItem('chatScrollToBottom') && box) {
        box.scrollTop = box.scrollHeight;
        sessionStorage.removeItem('chatScrollToBottom');
    }
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
