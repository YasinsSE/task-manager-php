<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Kullanıcı oturumu bilgisi
$user_email = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Stil dosyası burada -->
    <script src="../assets/css/scripts.js"></script>

</head>
<body class="dashboard">
    <!-- Logout Butonu -->
    <div class="logout-button">
        <a href="logout.php">Logout</a>
    </div>

    <div class="admin-tasks">
        <h2>Admin Tasks</h2>
        <div class="task-item">
            <p>Complete project timeline</p>
        </div>
        <div class="task-item">
            <p>Prepare budget analysis</p>
        </div>
        <div class="task-item">
            <p>Team meeting at 2 PM</p>
        </div>
        <div class="task-item">
            <p>Update client requirements</p>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="kanban-board">
            <h2>Task Board</h2>
            <div class="kanban-columns">
                <!-- To Do Column -->
                <div class="kanban-column">
                    <h2>To Do</h2>
                    <div class="kanban-card">
                        <p>Design the login page</p>
                        <div class="card-footer">
                            <span>👤 2 Assignees</span>
                            <span>📅 Dec 10</span>
                        </div>
                    </div>
                    <div class="kanban-add-card" onclick="openModal('todo')">+ Add a card</div>
                </div>

                <!-- Doing Column -->
                <div class="kanban-column">
                    <h2>Doing</h2>
                    <div class="kanban-card">
                        <p>Develop the dashboard</p>
                        <div class="card-footer">
                            <span>👤 3 Assignees</span>
                            <span>📅 Dec 7</span>
                        </div>
                    </div>
                    <div class="kanban-add-card" onclick="openModal('doing')">+ Add a card</div>
                </div>

                <!-- Done Column -->
                <div class="kanban-column">
                    <h2>Done</h2>
                    <div class="kanban-card">
                        <p>Set up project structure</p>
                        <div class="card-footer">
                            <span>👤 2 Assignees</span>
                            <span>✔ Completed</span>
                        </div>
                    </div>
                    <div class="kanban-add-card" onclick="openModal('done')">+ Add a card</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addTaskModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>
        <h2>Add a New Task</h2>
        <form action="card.php" method="POST">
            <input type="hidden" name="column" id="taskColumn">
            <div class="form-group">
                <label for="task_title">Task Title</label>
                <input type="text" id="task_title" name="task_title" placeholder="Enter task title" required>
            </div>
            <div class="form-group">
                <label for="task_description">Task Description</label>
                <textarea id="task_description" name="task_description" placeholder="Enter task description" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Add Task</button>
        </form>
    </div>
</div>
<script>
        // Modal Kontrolü
        function openModal(column) {
            document.getElementById('taskColumn').value = column;
            document.getElementById('addTaskModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addTaskModal').style.display = 'none';
        }
    </script>
</body>
</html>