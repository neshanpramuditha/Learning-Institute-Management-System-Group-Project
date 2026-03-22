<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'university_lms');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Helper function to check if user is student
function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

// Helper function to redirect
function redirect($page) {
    header("Location: $page");
    exit();
}

// Helper function to sanitize input
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}
?>