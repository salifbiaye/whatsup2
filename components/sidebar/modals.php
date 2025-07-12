<?php
// Variables attendues : $modalId, $user, $contacts_for_modal
?>
<?php
// Inclusion des modales
$modalId = 'modalUpdateUser';
include __DIR__ . '/modal_update_user.php';

$modalId = 'modalCreateContact';
include __DIR__ . '/modal_create_contact.php';

$modalId = 'modalCreateGroup';
include __DIR__ . '/modal_create_group.php';
?>
<script>
// Gestion des overlays pour toutes les modales
['modalUpdateUser', 'modalCreateContact', 'modalCreateGroup'].forEach(function(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    }
});

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('hidden');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
}
</script>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('hidden');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
}
</script>
