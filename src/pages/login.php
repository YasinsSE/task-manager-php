<?php
session_start();
require_once '../config/db.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Kullanıcıyı e-posta ile kontrol et
    $stmt = $conn->prepare("SELECT UserPassword FROM users WHERE UserEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['UserPassword'])) {
            $_SESSION['user'] = $email; // Oturum başlat
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Hatalı şifre.";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h1>Log In</h1>
            <!-- Form Yapısı -->
            <form action="login.php" method="POST">
                <div class="login-input">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="login-input">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Log In</button>
            </form>
            <!-- Hata Mesajı -->
            <p class="error-message">User not found.</p>
            <!-- Kayıt Olmadıysan Bölümü -->
            <div class="signup-section">
                <p>Don't already have an account ?</p> 
                <a href="signup.php" class="signup-btn">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
