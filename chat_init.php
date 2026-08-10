<?php
session_start();
include 'includes/db.php';

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

function initChatSession($conn) {
    // Check if user is logged in as patient (not admin)
    $is_patient = isset($_SESSION['user_id']) && !isset($_SESSION['admin_logged_in']);
    
    if (!$is_patient) {
        return ['success' => false, 'error' => 'Not logged in as patient'];
    }
    
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'] ?? '';
    $user_email = $_SESSION['user_email'] ?? '';
    
    // Get user details if not in session
    if (empty($user_name) || empty($user_email)) {
        $user_query = $conn->query("SELECT fullname, email FROM users WHERE id = $user_id");
        if ($user_query && $user_query->num_rows > 0) {
            $user = $user_query->fetch_assoc();
            $user_name = $user['fullname'];
            $user_email = $user['email'];
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
        }
    }
    
    // Generate or retrieve session token
    if (!isset($_SESSION['chat_token'])) {
        $_SESSION['chat_token'] = bin2hex(random_bytes(16));
    }
    $session_token = $_SESSION['chat_token'];
    
    // Check if active session exists
    $check_session = $conn->query("SELECT id FROM chat_sessions WHERE session_token = '$session_token' AND status != 'closed'");
    
    if ($check_session && $check_session->num_rows > 0) {
        $row = $check_session->fetch_assoc();
        $session_id = $row['id'];
    } else {
        // Create new session
        $conn->query("INSERT INTO chat_sessions (user_id, session_token, visitor_name, visitor_email, status, started_at) 
                      VALUES ($user_id, '$session_token', '$user_name', '$user_email', 'active', NOW())");
        $session_id = $conn->insert_id;
    }
    
    // Get unread count
    $unread_query = $conn->query("SELECT COUNT(*) as unread FROM chat_messages WHERE session_id = $session_id AND sender_type = 'admin' AND is_read = 0");
    $unread = $unread_query->fetch_assoc()['unread'];
    
    return [
        'success' => true,
        'session_id' => $session_id,
        'user_name' => $user_name,
        'unread_count' => $unread
    ];
}

if ($action == 'init_session') {
    $result = initChatSession($conn);
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>