<?php
session_start();
require_once '../config/db.php';


$currentUserId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['role'] ?? null;

if (!$currentUserId) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}


if ($userRole) {
    echo json_encode(['role' => $userRole]);
    exit;
}


$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo json_encode(['role' => $user['role']]);

    $_SESSION['role'] = $user['role'];
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
