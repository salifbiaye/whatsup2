// Emoji Button CDN
document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('#emoji-btn');
    const input = document.querySelector('#chat-input');
    if (button && input) {
        const picker = new EmojiButton({
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            zIndex: 9999
        });
        button.addEventListener('click', () => {
            picker.togglePicker(button);
        });
        picker.on('emoji', emoji => {
            input.value += emoji;
            input.focus();
        });
    }
    // Stickers
    const stickerBtn = document.getElementById('sticker-btn');
    const form = document.getElementById('chat-form');
    if (stickerBtn && form) {
        const stickerPanel = document.createElement('div');
        stickerPanel.className = 'absolute bottom-16 left-16 bg-white dark:bg-gray-900 border dark:border-gray-700 rounded shadow-lg p-3 flex gap-2 z-50';
        stickerPanel.style.display = 'none';
        const stickers = [
            '/whatsup2/storage/stickers/smile.png',
            '/whatsup2/storage/stickers/heart.png',
            '/whatsup2/storage/stickers/thumbsup.png',
            '/whatsup2/storage/stickers/laugh.png'
        ];
        stickers.forEach(src => {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'w-12 h-12 cursor-pointer hover:scale-110 transition';
            img.onclick = () => {
                const stickerInput = document.createElement('input');
                stickerInput.type = 'hidden';
                stickerInput.name = 'sticker';
                stickerInput.value = src;
                form.appendChild(stickerInput);
                form.submit();
                stickerPanel.style.display = 'none';
                setTimeout(() => stickerInput.remove(), 1000);
            };
            stickerPanel.appendChild(img);
        });
        document.body.appendChild(stickerPanel);
        stickerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            stickerPanel.style.display = stickerPanel.style.display === 'none' ? 'flex' : 'none';
            const rect = stickerBtn.getBoundingClientRect();
            stickerPanel.style.left = (rect.left - 10) + 'px';
            stickerPanel.style.bottom = (window.innerHeight - rect.top + 50) + 'px';
        });
        document.addEventListener('click', (e) => {
            if (!stickerPanel.contains(e.target) && e.target !== stickerBtn) {
                stickerPanel.style.display = 'none';
            }
        });
    }
});
