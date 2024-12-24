<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$currentUserId = $_SESSION['user_id'];
$currentFirstName = $_SESSION['first_name'] ?? '';
$currentLastName = $_SESSION['last_name'] ?? '';
?>
