<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $teamid = htmlspecialchars(trim($_POST['teamid']));

    $valid_email = 'admin@example.com';
    $valid_teamid = '2024001';

    if (empty($email) || empty($teamid)) {
        $error_message = "Both fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($email !== $valid_email || $teamid !== $valid_teamid) {
        $error_message = "Email and Team ID do not match our records.";
    } else {
        $success_message = "Password reset requested. Please check your email for further instructions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Task Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h1>Reset Your Password</h1>
            <p class="subtitle">Enter your email and Team ID to request a password reset</p>
            <?php if (isset($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } elseif (isset($success_message)) { ?>
                <p class="success"><?php echo $success_message; ?></p>
            <?php } ?>
            <?php if (!isset($success_message)) { ?>
                <form action="reset_password.php" method="POST">
                    <div class="login-input">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="login-input">
                        <input type="text" name="teamid" placeholder="Team ID" required>
                    </div>
                    <button type="submit">Request Password Reset</button>
                </form>
            <?php } ?>
            <p><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
