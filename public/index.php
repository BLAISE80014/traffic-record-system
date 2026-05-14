<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Include controller
require_once '../app/Controllers/AuthController.php';

// Handle requests
$authController = new AuthController();
$authController->signup();
$authController->login();

// Show login view
require_once '../app/Views/auth/login.php';
?>
