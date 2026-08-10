<?php
session_start();
include 'includes/db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];
$user_email = $user['email'];

// Fetch messages from this user
$query = "SELECT * FROM contact_messages WHERE email = '$user_email' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Messages - Florida Medical Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        /* Top Bar */
        .top-bar {
            background: rgba(255, 255, 255, 0.98);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar h2 {
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-bar h2 i {
            color: #667eea;
            font-size: 24px;
        }

        .top-bar-buttons {
            display: flex;
            gap: 12px;
        }

        .back-btn, .logout-btn {
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn {
            background: #667eea;
            color: white;
        }

        .back-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Container */
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-header p {
            color: rgba(255,255,255,0.8);
            margin-top: 8px;
            font-size: 14px;
        }

        /* Message Card */
        .message-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
            backdrop-filter: blur(5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .message-header {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        .message-subject {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message-subject i {
            font-size: 20px;
            color: #667eea;
        }

        .message-subject span {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 16px;
        }

        .message-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-unread { 
            background: #f8d7da; 
            color: #721c24;
        }

        .status-read { 
            background: #cce5ff; 
            color: #004085;
        }

        .status-replied { 
            background: #d4edda; 
            color: #155724;
        }

        .message-date {
            font-size: 12px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .message-date i {
            font-size: 12px;
        }

        /* Message Content */
        .message-content {
            padding: 25px;
        }

        .user-message {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .user-message strong {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1a1a2e;
            margin-bottom: 12px;
        }

        .user-message strong i {
            color: #667eea;
        }

        .user-message p {
            color: #555;
            line-height: 1.7;
            font-size: 14px;
        }

        /* Admin Reply */
        .admin-reply {
            background: #e8f4f8;
            padding: 20px;
            border-radius: 16px;
            border-left: 4px solid #28a745;
        }

        .reply-label {
            font-weight: 700;
            color: #004085;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reply-label i {
            font-size: 16px;
        }

        .admin-reply p {
            color: #333;
            line-height: 1.7;
            font-size: 14px;
        }

        .reply-date {
            margin-top: 12px;
            font-size: 12px;
            color: #28a745;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Status Info */
        .status-info {
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-info.unread {
            background: #fff3cd;
            color: #856404;
        }

        .status-info.read {
            background: #cce5ff;
            color: #004085;
        }

        .status-info i {
            font-size: 18px;
        }

        /* No Messages */
        .no-messages {
            text-align: center;
            padding: 60px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }

        .no-messages i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .no-messages p {
            color: #888;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .new-message-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .new-message-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Decorative Element */
        .bg-decoration {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 100px;
            pointer-events: none;
            z-index: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-bar {
                padding: 12px 20px;
            }

            .top-bar h2 {
                font-size: 18px;
            }

            .top-bar h2 i {
                font-size: 20px;
            }

            .back-btn, .logout-btn {
                padding: 6px 14px;
                font-size: 12px;
            }

            .message-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .message-meta {
                width: 100%;
                justify-content: space-between;
            }

            .page-header h2 {
                font-size: 24px;
            }

            .message-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Decorative background element -->
<div class="bg-decoration">
    <i class="fas fa-envelope"></i>
</div>

<div class="top-bar">
    <h2>
        <i class="fas fa-envelope"></i>
        My Messages
    </h2>
    <div class="top-bar-buttons">
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h2>
            <i class="fas fa-comment-dots"></i>
            Your Conversation History
        </h2>
        <p>View all your messages and responses from our support team</p>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
            $status = $row['status'];
            $status_icon = '';
            $status_class = '';
            switch($status) {
                case 'unread':
                    $status_icon = 'fa-envelope';
                    $status_class = 'unread';
                    break;
                case 'read':
                    $status_icon = 'fa-eye';
                    $status_class = 'read';
                    break;
                case 'replied':
                    $status_icon = 'fa-reply-all';
                    $status_class = 'read';
                    break;
                default:
                    $status_icon = 'fa-envelope';
            }
            ?>
            <div class="message-card">
                <div class="message-header">
                    <div class="message-subject">
                        <i class="fas fa-tag"></i>
                        <span><?php echo ucfirst(htmlspecialchars($row['subject'])); ?></span>
                    </div>
                    <div class="message-meta">
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                        <span class="message-date">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?>
                        </span>
                    </div>
                </div>
                <div class="message-content">
                    <div class="user-message">
                        <strong>
                            <i class="fas fa-user"></i> Your Message:
                        </strong>
                        <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                    </div>
                    
                    <?php if ($row['admin_reply']): ?>
                        <div class="admin-reply">
                            <div class="reply-label">
                                <i class="fas fa-reply"></i> Admin Response:
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                            <div class="reply-date">
                                <i class="fas fa-check-circle"></i>
                                Response received on <?php echo date('F d, Y', strtotime($row['updated_at'] ?? $row['created_at'])); ?>
                            </div>
                        </div>
                    <?php elseif ($row['status'] == 'unread'): ?>
                        <div class="status-info unread">
                            <i class="fas fa-clock"></i>
                            Your message is pending review. We'll respond within 24 hours.
                        </div>
                    <?php elseif ($row['status'] == 'read'): ?>
                        <div class="status-info read">
                            <i class="fas fa-eye"></i>
                            Your message has been read. A response is being prepared.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-messages">
            <i class="fas fa-inbox"></i>
            <p>📭 You haven't sent any messages yet.</p>
            <a href="contact.php" class="new-message-btn">
                <i class="fas fa-paper-plane"></i> Send a Message
            </a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>