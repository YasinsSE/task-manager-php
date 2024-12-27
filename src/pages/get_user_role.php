<?php
session_start();
require_once '../config/db.php'; // Veritabanı bağlantısı için gerekli dosya

// Kullanıcının oturumdaki ID'sini alın
$currentUserId = $_SESSION['user_id'] ?? null; // `$_SESSION['user_id']` giriş sırasında ayarlandı
$userRole = $_SESSION['role'] ?? null; // `$_SESSION['role']` giriş sırasında ayarlandı

if (!$currentUserId) {
    echo json_encode(['error' => 'User not logged in']); // Kullanıcı giriş yapmamış
    exit;
}

// Oturumdaki role bilgisini döndür
if ($userRole) {
    echo json_encode(['role' => $userRole]); // Oturumdan role bilgisini döndür
    exit;
}

// Eğer oturumda role yoksa, veritabanından kontrol et
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Veritabanından alınan role bilgisini JSON olarak döndür
    echo json_encode(['role' => $user['role']]);

    // Oturuma role bilgisini kaydet
    $_SESSION['role'] = $user['role'];
} else {
    echo json_encode(['error' => 'User not found']); // Kullanıcı bulunamadı
}

$stmt->close();
$conn->close();
?>
