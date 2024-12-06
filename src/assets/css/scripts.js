
function openModal(column) {
    document.getElementById('taskColumn').value = column; // Sütun bilgisi formda gizli olarak gönderilir
    document.getElementById('addTaskModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('addTaskModal').style.display = 'none';
}
