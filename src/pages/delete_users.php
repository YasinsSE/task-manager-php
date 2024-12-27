<?php
require_once '../config/db.php';
header("Access-Control-Allow-Origin: *"); // Tüm domainlere izin ver
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // İzin verilen HTTP metodları
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // İzin verilen header'lar

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'] ?? null;

    if ($userId) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
