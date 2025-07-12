<?php
// Variables attendues : $userId (id de l'utilisateur actuel)
?>
<div class="mb-4">
    <div class="relative">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Rechercher un contact..." 
            class="w-full px-3 py-2 rounded bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:text-gray-100"
        >
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const contactsList = document.querySelector('.contacts-list');
    let originalContactsHTML = contactsList.innerHTML;

    if (!contactsList) {
        console.error('La liste des contacts n\'a pas été trouvée');
        return;
    }

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        
        if (searchTerm === '') {
            // Si la recherche est vide, on réaffiche la liste originale
            contactsList.innerHTML = originalContactsHTML;
            return;
        }

        const contacts = Array.from(contactsList.children);
        
        contacts.forEach(contact => {
            const displayName = contact.querySelector('span').textContent.toLowerCase();
            const avatar = contact.querySelector('img').alt.toLowerCase();
            
            if (displayName.includes(searchTerm) || avatar.includes(searchTerm)) {
                contact.style.display = '';
            } else {
                contact.style.display = 'none';
            }
        });

        // Afficher un message si aucun contact n'est trouvé
        const visibleContacts = contacts.filter(contact => contact.style.display !== 'none');
        if (visibleContacts.length === 0) {
            contactsList.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-center mt-12">Aucun contact trouvé</div>';
        }
    });

    // Réinitialiser la recherche quand on clique en dehors
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target)) {
            searchInput.value = '';
            contactsList.innerHTML = originalContactsHTML;
        }
    });

    // Réinitialiser la recherche quand on appuie sur la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            searchInput.value = '';
            contactsList.innerHTML = originalContactsHTML;
        }
    });
});
</script>
