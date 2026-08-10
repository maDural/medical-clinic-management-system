<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get admin info from session
$admin_username = $_SESSION['admin_username'];

// First, try to find an existing user account for this admin
$patient_id = null;
$patient_name = null;
$patient_email = null;

// Search for user account by username (fullname)
$check_user = $conn->query("SELECT id, fullname, email FROM users WHERE fullname = '$admin_username'");

if ($check_user && $check_user->num_rows > 0) {
    // Found existing user account
    $user_data = $check_user->fetch_assoc();
    $patient_id = $user_data['id'];
    $patient_name = $user_data['fullname'];
    $patient_email = $user_data['email'];
} else {
    // No user account found, create one
    $unique_email = strtolower(str_replace(' ', '_', $admin_username)) . '_admin@patient.floridamedical.com';
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    
    $conn->query("INSERT INTO users (fullname, email, password) VALUES ('$admin_username', '$unique_email', '$hashed_password')");
    $patient_id = $conn->insert_id;
    $patient_name = $admin_username;
    $patient_email = $unique_email;
}

// Store original admin info for restoration
$_SESSION['original_admin_id'] = $_SESSION['admin_logged_in'];
$_SESSION['original_admin_username'] = $_SESSION['admin_username'];
$_SESSION['original_admin_email'] = $_SESSION['admin_email'] ?? '';

// Store admin session ID
$_SESSION['admin_session_id'] = session_id();

// Set patient session
$_SESSION['user_id'] = $patient_id;
$_SESSION['user_name'] = $patient_name;
$_SESSION['user_email'] = $patient_email;
$_SESSION['impersonating'] = true;

// Remove admin flags
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);

// Redirect to patient dashboard
header("Location: dashboard.php?patient_view=1");
exit();
?>