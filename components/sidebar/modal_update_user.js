// JS pour fermer la modale update_user en cliquant sur l'overlay
if (document.getElementById('modal-update-user')) {
  document.getElementById('modal-update-user').addEventListener('click', function(e) {
    if (e.target === this) closeModal('modal-update-user');
  });
}
