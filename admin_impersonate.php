<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    // Verify user exists
    $check_user = $conn->query("SELECT id, fullname, email FROM users WHERE id = $user_id");
    
    if ($check_user && $check_user->num_rows > 0) {
        $user = $check_user->fetch_assoc();
        
        // Store original admin info
        $_SESSION['original_admin_id'] = $_SESSION['admin_logged_in'];
        $_SESSION['original_admin_username'] = $_SESSION['admin_username'];
        $_SESSION['original_admin_fullname'] = $_SESSION['admin_fullname'] ?? 'Admin';
        
        // Store admin session ID to restore later
        $_SESSION['admin_session_id'] = session_id();
        
        // Clear admin session and set patient session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $user['fullname'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['impersonating'] = true;
        
        // Remove admin flags
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_username']);
        
        // Redirect to patient dashboard with impersonation notice
        header("Location: dashboard.php?impersonating=1");
        exit();
    } else {
        $_SESSION['impersonate_error'] = "Patient not found!";
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>