<?php
session_start();
require_once '../config/db.php'; // Database connection
require_once '../config/functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));
    $companyID = htmlspecialchars(trim($_POST['companyid']));

    // Error handling
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($companyID)) {
        $error_message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } elseif ($password !== $confirmPassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (CompanyID, FirstName, LastName, UserEmail, UserPassword) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $companyID, $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit;
        } else {
            $error_message = "Error: " . $stmt->error; // Show detailed error
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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
                    <input type="text" name="firstName" placeholder="Name" required>
                </div>
                <div class="login-input">
                    <input type="text" name="lastName" placeholder="Surname" required>
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