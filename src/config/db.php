<?php
$servername = "localhost"; // Workbench ve XAMPP için genelde localhost
$username = "root";        // Varsayılan kullanıcı adı
$password = "";            // Varsayılan şifre (XAMPP'de genelde boştur)
$dbname = "taskmanagementdb"; // Sizin veritabanı adınız

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>
