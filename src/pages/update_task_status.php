<?php
require_once '../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $taskId = filter_input(INPUT_POST, 'taskId', FILTER_VALIDATE_INT);
    $taskStatus = filter_input(INPUT_POST, 'taskStatus', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    

    if (!$taskId || !$taskStatus) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    if ($taskStatus === 'Unassigned') {
      
        echo json_encode([
            'success' => false,
            'message' => 'This task cannot be set back to Unassigned.'
        ]);
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE tasks SET taskStatus = ? WHERE taskId = ?");
    $stmt->bind_param("si", $taskStatus, $taskId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task status.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
