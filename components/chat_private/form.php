<form method="post" enctype="multipart/form-data" class="flex items-center gap-2 mt-2 bg-white dark:bg-gray-900 rounded-full px-3 py-2 shadow border dark:border-gray-700" id="chat-form">
    <label for="file-upload" class="cursor-pointer flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586" />
        </svg>
        <input id="file-upload" type="file" name="file" class="hidden" onchange="updateFileName(this)">
    </label>
   
    <input type="text" name="text" id="chat-input" placeholder="Votre message..." autocomplete="off" class="flex-1 px-4 py-2 bg-transparent border-none focus:ring-0 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 outline-none" />
    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-amber-600 hover:bg-amber-700 transition-colors text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </button>
</form>
<div id="file-name-preview" class="flex items-center gap-2 mt-2 text-sm text-gray-600 dark:text-gray-300"></div>
<script>
function updateFileName(input) {
    const preview = document.getElementById('file-name-preview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const name = input.files[0].name;
        const span = document.createElement('span');
        span.textContent = name;
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.innerHTML = '&times;';
        remove.className = 'ml-2 px-1 rounded hover:bg-red-100 dark:hover:bg-red-900 text-red-500 text-lg font-bold';
        remove.onclick = function() {
            input.value = '';
            preview.innerHTML = '';
        };
        preview.appendChild(span);
        preview.appendChild(remove);
    }
}
</script>
