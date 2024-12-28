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
    <!-- Left Menu Section -->
<div class="menu">
    <h2>Task Management System</h2>
    <a href="dashboard.php?view=all_tasks" class="<?= $view === 'all_tasks' ? 'active' : ''; ?>">All Tasks</a>
    <a href="dashboard.php?view=my_tasks" class="<?= $view === 'my_tasks' ? 'active' : ''; ?>">My Tasks</a>
    <button id="manageTaskBtn">Manage Task</button>
    <button id="manageEmployeeBtn">Manage Employee</button>
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

<!-- Pop-up ve Overlay -->
<div id="overlay" class="popup-overlay hidden"></div>

<!-- Manage Task Pop-up -->
<div class="popup hidden" id="manage-task-popup">
    <div class="popup-content">
        <h3>Manage Task</h3>
        <ul id="task-list">
        <li class="task-item">
        <div class="task-row">
          <span class="task-text">Design Login Page - Assigned to: null - Status: TODO</span>
          <button class="edit-button">Edit</button>
        </div>
      </li>
      <li class="task-item">
        <div class="task-row">
          <span class="task-text">Backend API Development - Assigned to: null - Status: In Progress</span>
          <button class="edit-button">Edit</button>
        </div>
      </li>
        </ul>
        <button class="popup-close" id="close-task-popup">Close</button>
    </div>
</div>

<!-- Manage Employee Pop-up -->
<div class="popup hidden" id="manage-employee-popup">
    <div class="popup-content">
        <h3>Manage Employee</h3>
        <ul id="employee-list"></ul>
        <button class="popup-close" id="close-employee-popup">Close</button>
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
        document.addEventListener('DOMContentLoaded', () => {
    const manageTaskPopup = document.getElementById('manage-task-popup');
    const manageEmployeePopup = document.getElementById('manage-employee-popup');
    const overlay = document.getElementById('overlay');
    const closeTaskPopup = document.getElementById('close-task-popup');
    const closeEmployeePopup = document.getElementById('close-employee-popup');
    const manageTaskBtn = document.getElementById('manageTaskBtn');
    const manageEmployeeBtn = document.getElementById('manageEmployeeBtn');
    const employeeList = document.getElementById('employee-list');
    

    // Pop-up açma fonksiyonu
    const openPopup = (popup) => {
        popup.classList.remove('hidden'); // Gizli sınıfını kaldır
        popup.classList.add('open'); // Açık sınıfını ekle
        overlay.classList.remove('hidden'); // Overlay'i görünür yap
        overlay.classList.add('open');
    };

    // Pop-up kapatma fonksiyonu
    const closePopup = (popup) => {
        popup.classList.remove('open'); // Açık sınıfını kaldır
        popup.classList.add('hidden'); // Gizli sınıfını ekle
        overlay.classList.remove('open'); // Overlay'i gizle
        overlay.classList.add('hidden');
    };

    // Manage Task butonuna tıklama
    manageTaskBtn.addEventListener('click', async () => {
        const role = await checkUserRole();
        if (role === 'Admin') {
            openPopup(manageTaskPopup);
        } else {
            alert('Access Denied: You do not have permission to manage tasks.');
        }
    });

    // Manage Employee butonuna tıklama
    manageEmployeeBtn.addEventListener('click', async () => {
        const role = await checkUserRole();
        if (role === 'Admin') {
            openPopup(manageEmployeePopup);
        } else {
            alert('Access Denied: You do not have permission to manage employees.');
        }
    });

    // Pop-up kapatma işlemleri
    closeTaskPopup.addEventListener('click', () => {
        closePopup(manageTaskPopup);
    });

    closeEmployeePopup.addEventListener('click', () => {
        closePopup(manageEmployeePopup);
    });

    // Overlay'e tıklama
    overlay.addEventListener('click', () => {
        closePopup(manageTaskPopup);
        closePopup(manageEmployeePopup);
    });

    // Kullanıcı rolünü kontrol eden fonksiyon
    const checkUserRole = async () => {
        try {
            const response = await fetch('get_user_role.php'); // get_user_role.php'yi çağır
            const data = await response.json();

            if (data.error) {
                console.error('Error:', data.error);
                return null;
            }

            console.log('User role:', data.role); // Debug için konsola yazdır
            return data.role;
        } catch (error) {
            console.error('Rol kontrol edilirken bir hata oluştu:', error);
            return null;
        }
    };
});

const loadEmployees = async () => {
    try {
        console.log('Fetching users...');
        const response = await fetch('http://localhost/TaskManagerPHP/src/pages/get_users.php');
        console.log('Fetch Response:', response);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const users = await response.json();
        console.log('Fetched Users:', users);

        const employeeList = document.getElementById('employee-list');
        employeeList.innerHTML = ''; // Listeyi temizle

        users.forEach(user => {
            console.log(`Adding user: ${user.firstName} ${user.lastName}`);
            const li = document.createElement('li');
            li.textContent = `${user.firstName} ${user.lastName} (${user.userEmail})`;

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.style.marginLeft = '10px';
            deleteButton.onclick = async () => {
                console.log(`Deleting user with ID: ${user.id}`);
                const deleteResponse = await fetch('http://localhost/TaskManagerPHP/src/pages/delete_users.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${user.id}`,
                });
                

                const deleteResult = await deleteResponse.json();
                console.log('Delete Result:', deleteResult);

                if (deleteResult.success) {
                    alert(deleteResult.message);
                    loadEmployees(); // Listeyi güncelle
                } else {
                    alert(deleteResult.message);
                }
            };

            li.appendChild(deleteButton);
            employeeList.appendChild(li);
        });
    } catch (error) {
        console.error('Error loading employees:', error);
    }
};

    document.getElementById('manageEmployeeBtn').addEventListener('click', () => {
        loadEmployees();
    });


    /* MANAGE TASK PART */

    const loadTasks = async () => {
    try {
        const response = await fetch('http://localhost/TaskManagerPHP/src/pages/get_tasks.php');
        const tasks = await response.json();

        const taskList = document.getElementById('task-list');
        taskList.innerHTML = ''; // Listeyi temizle

        tasks.forEach(task => {
            const li = document.createElement('li');
    li.className = 'task-item';

    // Görev satırı için bir div oluştur
    const taskRow = document.createElement('div');
    taskRow.className = 'task-row';

    // Görev metni
    const taskText = document.createElement('span');
    taskText.textContent = `${task.taskTitle} - Assigned to: ${task.assignedUserId} - Status: ${task.taskStatus}`;
    taskText.className = 'task-text';

    // Edit butonu
    const editButton = document.createElement('button');
    editButton.textContent = 'Edit';
    editButton.classList.add('edit-button');

    editButton.onclick = () => {
        const newTitle = prompt('New Task Title:', task.taskTitle);
        const newAssignedUserId = prompt('New Assigned User ID:', task.assignedUserId);
        const newStatus = prompt('New Task Status:', task.taskStatus);

        if (newTitle && newAssignedUserId && newStatus) {
            updateTask(task.taskId, newTitle, newAssignedUserId, newStatus);
        }
    };

    // Div'e öğeleri ekle
    taskRow.appendChild(taskText);
    taskRow.appendChild(editButton);

    // Li'ye div'i ekle
    li.appendChild(taskRow);

    // Listeye ekle
    taskList.appendChild(li);
        });
    } catch (error) {
        console.error('Error loading tasks:', error);
    }
};

const updateTask = async (taskId, taskTitle, assignedUserId, taskStatus) => {
    try {
        const response = await fetch('http://localhost/TaskManagerPHP/src/pages/update_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `taskId=${taskId}&taskTitle=${taskTitle}&assignedUserId=${assignedUserId}&taskStatus=${taskStatus}`,
        });

        const result = await response.json();
        alert(result.message);

        if (result.success) {
            loadTasks(); // Listeyi güncelle
        }
    } catch (error) {
        console.error('Error updating task:', error);
    }
};

document.getElementById('manageTaskBtn').addEventListener('click', () => {
    loadTasks();
});

    </script>
</body>
</html>
