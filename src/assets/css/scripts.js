
function openModal(column) {
    document.getElementById('taskColumn').value = column;
    document.getElementById('addTaskModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('addTaskModal').style.display = 'none';
}

