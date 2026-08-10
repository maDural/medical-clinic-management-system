<?php
session_start();

// Check if we're currently impersonating
if (isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true) {
    
    // Restore admin session
    if (isset($_SESSION['original_admin_id'])) {
        $_SESSION['admin_logged_in'] = $_SESSION['original_admin_id'];
        $_SESSION['admin_username'] = $_SESSION['original_admin_username'];
        $_SESSION['admin_email'] = $_SESSION['original_admin_email'] ?? '';
        
        // Clear impersonation data
        unset($_SESSION['original_admin_id']);
        unset($_SESSION['original_admin_username']);
        unset($_SESSION['original_admin_email']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['impersonating']);
        unset($_SESSION['admin_session_id']);
        
        // Set success message for admin dashboard
        $_SESSION['admin_message'] = "You have returned to admin mode.";
    }
}

// Redirect back to admin dashboard
header("Location: admin_dashboard.php");
exit();
?>