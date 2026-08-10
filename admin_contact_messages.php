<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle status update and reply
if (isset($_POST['update_status'])) {
    $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $admin_reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
    
    $get_message = "SELECT fullname, email, subject, message FROM contact_messages WHERE id = '$message_id'";
    $msg_result = $conn->query($get_message);
    $msg = $msg_result->fetch_assoc();
    
    $update_query = "UPDATE contact_messages SET status = '$status', admin_reply = '$admin_reply' WHERE id = '$message_id'";
    
    if ($conn->query($update_query)) {
        $success = "Message status updated successfully!";
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $message_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM contact_messages WHERE id = '$message_id'";
    
    if ($conn->query($delete_query)) {
        $success = "Message deleted successfully!";
        header("refresh:1;url=admin_contact_messages.php");
    } else {
        $error = "Error deleting message: " . $conn->error;
    }
}

// Fetch all contact messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");

$total = $conn->query("SELECT COUNT(*) as total FROM contact_messages")->fetch_assoc()['total'];
$unread = $conn->query("SELECT COUNT(*) as unread FROM contact_messages WHERE status = 'unread'")->fetch_assoc()['unread'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(26, 41, 128, 0.85) 0%, rgba(38, 208, 206, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }
        .top-bar {
            background: rgba(255, 255, 255, 0.98);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-bar h1 { font-size: 22px; font-weight: 600; display: flex; align-items: center; gap: 12px; color: #1a1a2e; }
        .nav-links { display: flex; gap: 12px; }
        .nav-links a { color: #1a1a2e; text-decoration: none; padding: 8px 18px; border-radius: 30px; transition: all 0.3s ease; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
        .nav-links a:hover { background: rgba(102, 126, 234, 0.1); transform: translateY(-2px); }
        .logout-btn { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
        .logout-btn:hover { background: #e74c3c; color: white; }
        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; position: relative; z-index: 1; }
        .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px; margin-bottom: 35px; }
        .stat-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 25px; border-radius: 20px; text-align: center; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.98); }
        .stat-number { font-size: 32px; font-weight: 800; color: #1a1a2e; }
        .stat-label { color: #666; font-size: 13px; font-weight: 500; margin-top: 5px; }
        .success, .error { padding: 14px 20px; border-radius: 12px; margin-bottom: 25px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .message-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; margin-bottom: 25px; overflow: hidden; transition: all 0.3s ease; }
        .message-card:hover { transform: translateY(-3px); background: rgba(255, 255, 255, 0.98); }
        .message-header { background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%); padding: 18px 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-bottom: 1px solid #eef2f6; }
        .message-info { display: flex; gap: 20px; flex-wrap: wrap; align-items: center; }
        .message-id { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-badge { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-unread { background: #f8d7da; color: #721c24; }
        .status-read { background: #cce5ff; color: #004085; }
        .status-replied { background: #d4edda; color: #155724; }
        .message-body { padding: 25px; }
        .message-content { background: #f8f9fa; padding: 15px; border-radius: 12px; margin: 10px 0; }
        .reply-box { background: #e8f4f8; padding: 15px; border-radius: 12px; margin-top: 15px; }
        .form-inline { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px; }
        select, textarea { padding: 10px 14px; border: 2px solid #eef2f6; border-radius: 12px; font-size: 14px; font-family: inherit; }
        textarea { flex: 1; min-width: 200px; resize: vertical; }
        .update-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 24px; border-radius: 12px; cursor: pointer; font-weight: 600; }
        .delete-btn { background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 12px; cursor: pointer; text-decoration: none; display: inline-block; }
        .empty-state { text-align: center; padding: 60px; background: rgba(255, 255, 255, 0.95); border-radius: 20px; }
        @media (max-width: 768px) {
            .top-bar { flex-direction: column; gap: 15px; text-align: center; }
            .message-header { flex-direction: column; align-items: flex-start; }
            .form-inline { flex-direction: column; }
            select, textarea, .update-btn, .delete-btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
        <div class="nav-links">
            <a href="admin_dashboard.php"><i class="fas fa-calendar-check"></i> Appointments</a>
            <a href="admin_medical_requests.php"><i class="fas fa-folder-medical"></i> Medical Records</a>
            <a href="admin_feedback.php"><i class="fas fa-comment-dots"></i> Feedback</a>
            <a href="admin_contact_messages.php" style="background: rgba(102, 126, 234, 0.1);"><i class="fas fa-envelope"></i> Messages</a>
            <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="stats-container">
            <div class="stat-card"><div class="stat-number"><?php echo $total; ?></div><div class="stat-label">Total Messages</div></div>
            <div class="stat-card"><div class="stat-number" style="color: #e74c3c;"><?php echo $unread; ?></div><div class="stat-label">Unread</div></div>
        </div>

        <?php if (isset($success)): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div class="message-info">
                            <span class="message-id">#<?php echo $row['id']; ?></span>
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['fullname']); ?></span>
                            <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                            <?php if ($row['phone']): ?>
                                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></span>
                            <?php endif; ?>
                            <span><i class="far fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></span>
                        </div>
                        <span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
                    </div>
                    <div class="message-body">
                        <div class="message-content"><strong>📋 Subject: <?php echo ucfirst(htmlspecialchars($row['subject'])); ?></strong><p style="margin-top: 10px;"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p></div>
                        <?php if ($row['admin_reply']): ?>
                            <div class="reply-box"><strong>✏️ Admin Reply:</strong><p style="margin-top: 8px;"><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p></div>
                        <?php endif; ?>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="unread" <?php echo $row['status'] == 'unread' ? 'selected' : ''; ?>>Unread</option>
                                <option value="read" <?php echo $row['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
                                <option value="replied" <?php echo $row['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
                                <option value="archived" <?php echo $row['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                            <textarea name="admin_reply" placeholder="Reply to this message..."><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                            <button type="submit" name="update_status" class="update-btn"><i class="fas fa-paper-plane"></i> Update</button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?')"><i class="fas fa-trash-alt"></i> Delete</a>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state"><i class="fas fa-inbox"></i><p>📭 No contact messages yet.</p></div>
        <?php endif; ?>
    </div>
</body>
</html>