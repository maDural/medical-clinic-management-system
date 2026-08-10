<?php
session_start();
include 'includes/db.php';

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// Check who is logged in
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$is_patient = isset($_SESSION['user_id']) && !isset($_SESSION['admin_logged_in']);

// Close session to allow concurrent requests
session_write_close();

switch($action) {
    case 'send_message':
        $session_id = intval($_POST['session_id']);
        $message = trim($_POST['message']);
        
        // Prevent empty messages
        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
            exit();
        }
        
        // Rate limiting - prevent duplicate messages within 2 seconds
        $rate_limit_key = "last_message_$session_id";
        session_start();
        if (isset($_SESSION[$rate_limit_key]) && time() - $_SESSION[$rate_limit_key] < 2) {
            session_write_close();
            echo json_encode(['success' => false, 'error' => 'Please wait before sending another message']);
            exit();
        }
        $_SESSION[$rate_limit_key] = time();
        session_write_close();
        
        $message = mysqli_real_escape_string($conn, $message);
        
        // Determine sender type correctly
        if ($is_admin) {
            $sender_type = 'admin';
            $sender_name = $_SESSION['admin_username'] ?? 'Admin';
        } elseif ($is_patient) {
            $sender_type = 'user';
            $sender_name = $_SESSION['user_name'] ?? 'Patient';
        } else {
            $sender_type = 'user';
            $sender_name = 'Guest';
        }
        
        $query = "INSERT INTO chat_messages (session_id, sender_type, sender_name, message, is_read, created_at) 
                  VALUES ($session_id, '$sender_type', '$sender_name', '$message', 0, NOW())";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'sender_type' => $sender_type, 'message_id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        break;
        
    case 'get_messages':
        $session_id = intval($_GET['session_id']);
        $last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
        
        // If admin, mark user messages as read when fetching
        if ($is_admin) {
            $conn->query("UPDATE chat_messages SET is_read = 1 WHERE session_id = $session_id AND sender_type = 'user' AND is_read = 0");
        }
        
        $query = "SELECT * FROM chat_messages WHERE session_id = $session_id";
        if ($last_id > 0) {
            $query .= " AND id > $last_id";
        }
        $query .= " ORDER BY id ASC";
        
        $result = $conn->query($query);
        $messages = [];
        
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'id' => $row['id'],
                'sender_type' => $row['sender_type'],
                'sender_name' => $row['sender_name'],
                'message' => $row['message'],
                'is_read' => (bool)$row['is_read'],
                'time' => date('h:i A', strtotime($row['created_at']))
            ];
        }
        
        echo json_encode(['messages' => $messages]);
        break;
        
    case 'typing':
        $session_id = intval($_POST['session_id']);
        session_start();
        $_SESSION['typing_' . $session_id] = time();
        session_write_close();
        echo json_encode(['success' => true]);
        break;
        
    case 'check_typing':
        $session_id = intval($_GET['session_id']);
        $typing = false;
        
        session_start();
        if (isset($_SESSION['typing_' . $session_id])) {
            $last_typing = $_SESSION['typing_' . $session_id];
            if (time() - $last_typing < 3) {
                $typing = true;
            }
        }
        session_write_close();
        
        echo json_encode(['typing' => $typing]);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>