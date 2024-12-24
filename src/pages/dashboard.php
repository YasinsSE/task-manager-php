<?php
require_once '../config/db.php';
require_once 'require_auth.php'; // Ensure the user is logged in


function fetchTasks($conn, $filter = null, $userId = null) {
    // Base query to fetch tasks with user information
    $query = "
        SELECT 
            tasks.taskId, 
            tasks.taskTitle, 
            tasks.taskDescription, 
            tasks.taskDueDate, 
            tasks.taskStatus, 
            tasks.assignedUserId,
            users.firstName,
            users.lastName
        FROM tasks
        LEFT JOIN users ON tasks.assignedUserId = users.id
    ";

    # Filter for user's tasks
    if ($filter === 'my_tasks' && $userId) {
        $query .= " WHERE tasks.assignedUserId = ?";
    }

    $stmt = $conn->prepare($query);

    if ($filter === 'my_tasks' && $userId) {
        $stmt->bind_param("i", $userId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all tasks
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['firstName']) && !empty($row['lastName'])) {
            $row['assignedUserName'] = $row['firstName'] . ' ' . substr($row['lastName'], 0, 1) . '.';
        } else {
            $row['assignedUserName'] = 'Unassigned';
        }
        $tasks[] = $row;
    }

    $stmt->close();
    return $tasks;
}


$view = $_GET['view'] ?? 'all_tasks'; 
$tasks = fetchTasks($conn, $view === 'my_tasks' ? 'my_tasks' : null, $currentUserId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard">
    <!-- Left Menu Section -->
    <div class="menu">
        <h2>Task Management System</h2>
        <a href="dashboard.php?view=all_tasks" class="<?= $view === 'all_tasks' ? 'active' : ''; ?>">All Tasks</a>
        <a href="dashboard.php?view=my_tasks" class="<?= $view === 'my_tasks' ? 'active' : ''; ?>">My Tasks</a>
        <div class="menu-bottom">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Right Content Section -->
    <div class="content">
    <!-- Task Status Panels -->
    <div class="task-block" id="waiting-for-assignment">
        <h3>Waiting for Assignment</h3>
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['taskStatus'] === 'Unassigned'): ?>
                <div class="task-item <?= $task['assignedUserId'] == $currentUserId ? 'draggable' : 'not-draggable'; ?>"
                    <?= $task['assignedUserId'] == $currentUserId ? 'draggable="true"' : ''; ?>>
                    <div class="task-header">
                        <p class="task-title"><?= htmlspecialchars($task['taskTitle']); ?></p>
                        <hr class="task-divider">
                    </div>
                    <div class="task-body">
                        <p class="task-description"><?= htmlspecialchars($task['taskDescription']); ?></p>
                    </div>
                    <div class="task-footer">
                        <p class="task-due-date">Due: <?= htmlspecialchars($task['taskDueDate']); ?></p>
                        <p class="task-assigned">
                            Assigned to: 
                            <?php if (!empty($task['firstName']) && !empty($task['lastName'])): ?>
                                <?= htmlspecialchars($task['firstName']) . ' ' . htmlspecialchars(substr($task['lastName'], 0, 1)) . '.'; ?>
                            <?php else: ?>
                                Unassigned
                            <?php endif; ?>
                        </p>
                    </div>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="task-block" id="todo">
        <h3>TODO</h3>
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['taskStatus'] === 'TODO'): ?>
                <div class="task-item <?= $task['assignedUserId'] == $currentUserId ? 'draggable' : 'not-draggable'; ?>"
                    <?= $task['assignedUserId'] == $currentUserId ? 'draggable="true"' : ''; ?>>
                    <div class="task-header">
                        <p class="task-title"><?= htmlspecialchars($task['taskTitle']); ?></p>
                        <hr class="task-divider">
                    </div>
                    <div class="task-body">
                        <p class="task-description"><?= htmlspecialchars($task['taskDescription']); ?></p>
                    </div>
                    <div class="task-footer">
                        <p class="task-due-date">Due: <?= htmlspecialchars($task['taskDueDate']); ?></p>
                        <p class="task-assigned">
                            Assigned to: 
                            <?php if (!empty($task['firstName']) && !empty($task['lastName'])): ?>
                                <?= htmlspecialchars($task['firstName']) . ' ' . htmlspecialchars(substr($task['lastName'], 0, 1)) . '.'; ?>
                            <?php else: ?>
                                Unassigned
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="task-block" id="in-progress">
        <h3>In Progress</h3>
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['taskStatus'] === 'In Progress'): ?>
                <div class="task-item <?= $task['assignedUserId'] == $currentUserId ? 'draggable' : 'not-draggable'; ?>"
                    <?= $task['assignedUserId'] == $currentUserId ? 'draggable="true"' : ''; ?>>
                    <div class="task-header">
                        <p class="task-title"><?= htmlspecialchars($task['taskTitle']); ?></p>
                        <hr class="task-divider">
                    </div>
                    <div class="task-body">
                        <p class="task-description"><?= htmlspecialchars($task['taskDescription']); ?></p>
                    </div>
                    <div class="task-footer">
                        <p class="task-due-date">Due: <?= htmlspecialchars($task['taskDueDate']); ?></p>
                        <p class="task-assigned">
                            Assigned to: 
                            <?php if (!empty($task['firstName']) && !empty($task['lastName'])): ?>
                                <?= htmlspecialchars($task['firstName']) . ' ' . htmlspecialchars(substr($task['lastName'], 0, 1)) . '.'; ?>
                            <?php else: ?>
                                Unassigned
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="task-block" id="done">
        <h3>Done</h3>
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['taskStatus'] === 'Done'): ?>
                <div class="task-item <?= $task['assignedUserId'] == $currentUserId ? 'draggable' : 'not-draggable'; ?>"
                    <?= $task['assignedUserId'] == $currentUserId ? 'draggable="true"' : ''; ?>>
                    <div class="task-header">
                        <p class="task-title"><?= htmlspecialchars($task['taskTitle']); ?></p>
                        <hr class="task-divider">
                    </div>
                    <div class="task-body">
                        <p class="task-description"><?= htmlspecialchars($task['taskDescription']); ?></p>
                    </div>
                    <div class="task-footer">
                        <p class="task-due-date">Due: <?= htmlspecialchars($task['taskDueDate']); ?></p>
                        <p class="task-assigned">
                            Assigned to: 
                            <?php if (!empty($task['firstName']) && !empty($task['lastName'])): ?>
                                <?= htmlspecialchars($task['firstName']) . ' ' . htmlspecialchars(substr($task['lastName'], 0, 1)) . '.'; ?>
                            <?php else: ?>
                                Unassigned
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

    <script>
        // Drag-and-Drop Functionality
        const tasks = document.querySelectorAll('.task-item.draggable');
        const blocks = document.querySelectorAll('.task-block');

        tasks.forEach(task => {
            task.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', e.target.id);
                task.classList.add('dragging');
            });

            task.addEventListener('dragend', () => {
                task.classList.remove('dragging');
            });
        });

        blocks.forEach(block => {
            block.addEventListener('dragover', e => {
                e.preventDefault();
                const draggingTask = document.querySelector('.dragging');
                if (draggingTask) {
                    block.appendChild(draggingTask);
                }
            });
        });
    </script>
</body>
</html>
