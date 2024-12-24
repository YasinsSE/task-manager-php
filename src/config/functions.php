<?php
// Kullanıcı kayıt fonksiyonu
function registerUser($conn, $firstName, $lastName, $email, $password, $teamID) {
    $hashedPassword = password_hash($password, algo: PASSWORD_DEFAULT);

    // SQL sorgusu (prepared statement ile)
    $sql = "INSERT INTO users (FirstName, LastName, UserEmail, UserPassword, TeamID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $teamID);

    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => "Registration successful! You can now log in."
        ];
    } else {
        if ($stmt->errno === 1062) { // Duplicate entry (E-posta veya TeamID zaten kayıtlı)
            if (strpos($stmt->error, 'UserEmail') !== false) {
                return [
                    'success' => false,
                    'message' => "This email address is already registered."
                ];
            } elseif (strpos($stmt->error, 'TeamID') !== false) {
                return [
                    'success' => false,
                    'message' => "This Team ID is already registered."
                ];
            }
        }
        return [
            'success' => false,
            'message' => "An error occurred: " . $stmt->error
        ];
    }
}


// Kullanıcının oturum durumunu kontrol etme
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Kullanıcıyı oturumdan çıkış yaptırma
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
