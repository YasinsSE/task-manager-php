<?php
require_once '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'] ?? null;
    $taskTitle = $_POST['taskTitle'] ?? null;
    $assignedUserId = $_POST['assignedUserId'] ?? null;
    $taskStatus = $_POST['taskStatus'] ?? null;

    if ($taskId && $taskTitle && $assignedUserId && $taskStatus) {
        $stmt = $conn->prepare("UPDATE tasks SET taskTitle = ?, assignedUserId = ?, taskStatus = ? WHERE taskId = ?");
        $stmt->bind_param("sisi", $taskTitle, $assignedUserId, $taskStatus, $taskId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update task']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
