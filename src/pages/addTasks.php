<?php
require_once '../config/db.php';

// CORS ayarları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$taskTitle = $_POST['taskTitle'] ?? null;
$taskDescription = $_POST['taskDescription'] ?? null;
$taskDueDate = $_POST['taskDueDate'] ?? null;


if (!$taskTitle || !$taskDescription || !$taskDueDate) {
    http_response_code(400);
    echo json_encode(['error' => 'Required fields are missing']);
    exit;
}


$query = "INSERT INTO tasks (taskTitle, taskDescription, taskDueDate) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query']);
    exit;
}


$stmt->bind_param("sss", $taskTitle, $taskDescription, $taskDueDate);
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['success' => true, 'taskId' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to insert task']);
}


$stmt->close();
$conn->close();
?>
