<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and trim input
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $companyid = htmlspecialchars(trim($_POST['companyid']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    $error_message = ''; // Initialize error message

    // Validate input fields
    if (empty($name) || empty($surname) || empty($email) || empty($companyid) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address. Please enter a valid format, e.g., example@domain.com.";
    } elseif (!preg_match('/^\d{7}$/', $companyid)) {
        $error_message = "Company ID must be a 7-digit number. If you don’t know your Company ID, please contact your manager.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please confirm your password.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }

    // If no errors, process further (e.g., save to database)
    if (empty($error_message)) {
        $_SESSION['signup_success'] = "Registration successful! Please log in.";
        header("Location: login.php"); // Redirect to login page
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Task Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h1>Create Your Account</h1>
            <p class="subtitle">Sign up to get started</p>
            <!-- Display error message dynamically -->
            <?php if (isset($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>
            <!-- Signup Form -->
            <form action="signup.php" method="POST">
                <div class="login-input">
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="login-input">
                    <input type="text" name="surname" placeholder="Surname" required>
                </div>
                <div class="login-input">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="login-input">
                    <input 
                        type="text" 
                        name="companyid" 
                        placeholder="Enter your Company ID" 
                        minlength="7" 
                        maxlength="7" 
                        pattern="\d{7}" 
                        title="Company ID must be a 7 digit number. Contact your manager if you don’t know it." 
                        required>
                </div>
                <div class="login-input">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="login-input">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>
    </div>
</body>
</html>
