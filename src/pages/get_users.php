<?php
require_once '../config/db.php';
header("Access-Control-Allow-Origin: *"); // Tüm domainlere izin ver
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // İzin verilen HTTP metodları
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // İzin verilen headerlar

$query = "SELECT id, firstName, lastName, userEmail FROM users";
$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed']);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
// JSON çıktısı
header('Content-Type: application/json');
echo json_encode($users);
$conn->close();
?>


