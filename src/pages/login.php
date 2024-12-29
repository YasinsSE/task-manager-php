<?php
session_start();
require_once '../config/db.php'; 

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if ($email && $password) {
        
        $stmt = $conn->prepare("SELECT id, userPassword, role FROM users WHERE userEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['userPassword'])) {
                
                session_regenerate_id(); 
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['email'] = $email; 
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                
                $error_message = "Invalid credentials. Please check your email and password.";
            }
        } else {
           
            $error_message = "Invalid credentials. Please check your email and password.";
        }

        $stmt->close();
    } else {
        
        $error_message = "Please provide a valid email and password.";
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
            <!-- Login Form -->
            <form action="login.php" method="POST">
                <div class="login-input">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="login-input">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Log In</button>
            </form>
            <!-- Display Error Message -->
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <!-- Signup Section -->
            <div class="signup-section">
                <p>Don't already have an account?</p> 
                <a href="signup.php" class="signup-btn">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
