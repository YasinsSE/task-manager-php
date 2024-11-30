<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    $valid_email = 'admin@example.com';
    $valid_password = 'password123';

    if ($email === $valid_email && $password === $valid_password) {
        $_SESSION['user'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = "Invalid email or password.";
        $forgot_password_link = true; // Show "Forgot Password"
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
            <h1>Collaborate, Organize, And Track Every Task</h1>
            <p class="subtitle">Please login to your account</p>
            <?php if (isset($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>
            <form action="login.php" method="POST">
                <div class="login-input">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="login-input">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($forgot_password_link)) { ?>
                <p class="forgot-password">Having trouble logging in? <a href="reset_password.php">Reset your password.</a></p>
            <?php } ?>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
