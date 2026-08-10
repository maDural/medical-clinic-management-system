<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Mark messages as read for a session
if (isset($_POST['mark_read'])) {
    $session_id = intval($_POST['session_id']);
    $conn->query("UPDATE chat_messages SET is_read = 1 WHERE session_id = $session_id AND sender_type = 'user' AND is_read = 0");
    echo json_encode(['success' => true]);
    exit();
}

// Get all active chat sessions with unread count
$sessions_query = "SELECT cs.*, 
                   COUNT(cm.id) as message_count,
                   SUM(CASE WHEN cm.sender_type = 'user' AND cm.is_read = 0 THEN 1 ELSE 0 END) as unread_count,
                   MAX(cm.created_at) as last_message
                   FROM chat_sessions cs
                   LEFT JOIN chat_messages cm ON cs.id = cm.session_id
                   WHERE cs.status = 'active'
                   GROUP BY cs.id
                   ORDER BY unread_count DESC, last_message DESC";
$sessions_result = $conn->query($sessions_query);

// Get selected session
$selected_session = isset($_GET['session']) ? intval($_GET['session']) : 0;

// If no session selected and there are sessions, select the first one
if ($selected_session == 0 && $sessions_result && $sessions_result->num_rows > 0) {
    $first_session = $sessions_result->fetch_assoc();
    $selected_session = $first_session['id'];
    $sessions_result->data_seek(0);
}

// Mark messages as read for selected session
if ($selected_session > 0) {
    $conn->query("UPDATE chat_messages SET is_read = 1 WHERE session_id = $selected_session AND sender_type = 'user' AND is_read = 0");
}

// Get total unread count for badge
$total_unread = $conn->query("SELECT COUNT(*) as total FROM chat_messages cm JOIN chat_sessions cs ON cm.session_id = cs.id WHERE cs.status = 'active' AND cm.sender_type = 'user' AND cm.is_read = 0")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat - Florida Medical Clinic</title>
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

        .top-bar h1 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1a1a2e;
        }

        .top-bar h1 i {
            font-size: 28px;
            color: #667eea;
        }

        .nav-links {
            display: flex;
            gap: 12px;
        }

        .nav-links a {
            color: #1a1a2e;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .logout-btn {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .logout-btn:hover {
            background: #e74c3c;
            color: white;
        }

        .chat-container {
            display: flex;
            height: calc(100vh - 80px);
            background: rgba(255, 255, 255, 0.95);
            margin: 20px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .sessions-sidebar {
            width: 320px;
            background: white;
            border-right: 1px solid #eef2f6;
            overflow-y: auto;
        }

        .sessions-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: sticky;
            top: 0;
        }

        .session-item {
            padding: 18px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .session-item:hover {
            background: #f8f9ff;
        }

        .session-item.active {
            background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
            border-left: 3px solid #667eea;
        }

        .session-name {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .session-name i {
            color: #667eea;
            font-size: 14px;
        }

        .session-info {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }

        .session-time {
            font-size: 11px;
            color: #aaa;
        }

        .unread-badge {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: #e74c3c;
            color: white;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            min-width: 22px;
            text-align: center;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }

        .chat-header {
            background: white;
            padding: 20px 25px;
            border-bottom: 1px solid #eef2f6;
        }

        .chat-header h3 {
            color: #1a1a2e;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header p {
            font-size: 13px;
            color: #888;
            margin-top: 6px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .message {
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.user {
            align-items: flex-start;
        }

        .message.admin {
            align-items: flex-end;
        }

        .message-sender {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
            padding-left: 5px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .message-bubble {
            max-width: 65%;
            padding: 12px 18px;
            border-radius: 20px;
            word-wrap: break-word;
            line-height: 1.5;
            font-size: 14px;
        }

        .message.user .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e8e8e8;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .message.user.unread .message-bubble {
            background: #fff3cd;
            border: 1px solid #ffc107;
        }

        .message.admin .message-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
        }

        .message-time {
            font-size: 10px;
            margin-top: 5px;
            padding-left: 8px;
            color: #aaa;
        }

        .mark-read-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            margin-top: 5px;
            margin-left: 5px;
            transition: all 0.3s ease;
        }

        .mark-read-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .typing-indicator {
            padding: 12px 25px;
            font-size: 12px;
            color: #667eea;
            font-style: italic;
            display: flex;
            align-items: center;
            gap: 8px;
            border-top: 1px solid #eef2f6;
            background: white;
        }

        .chat-input {
            padding: 20px 25px;
            background: white;
            border-top: 1px solid #eef2f6;
            display: flex;
            gap: 12px;
        }

        .chat-input input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #eef2f6;
            border-radius: 30px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .chat-input input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .chat-input button {
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-input button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .chat-messages::-webkit-scrollbar,
        .sessions-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track,
        .sessions-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .chat-messages::-webkit-scrollbar-thumb,
        .sessions-sidebar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .no-session {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #aaa;
            font-size: 16px;
            gap: 15px;
        }

        .no-session i {
            font-size: 64px;
            color: #ddd;
        }

        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
                margin: 10px;
                height: calc(100vh - 70px);
            }
            .sessions-sidebar {
                width: 100%;
                max-height: 250px;
                border-right: none;
                border-bottom: 1px solid #eef2f6;
            }
            .message-bubble { max-width: 85%; }
            .top-bar { flex-direction: column; gap: 15px; text-align: center; }
            .top-bar h1 { font-size: 18px; }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>
            <i class="fas fa-comments"></i>
            Live Chat Support
            <span style="font-size: 12px; background: rgba(102, 126, 234, 0.1); padding: 2px 10px; border-radius: 20px; color: #667eea;">
                <?php echo $total_unread > 0 ? $total_unread . ' unread' : 'All caught up'; ?>
            </span>
        </h1>
        <div class="nav-links">
            <a href="admin_dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="admin_logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="chat-container">
        <div class="sessions-sidebar">
            <div class="sessions-header">
                <i class="fas fa-users"></i> Active Chats
            </div>
            <?php if ($sessions_result && $sessions_result->num_rows > 0): ?>
                <?php while ($session = $sessions_result->fetch_assoc()): ?>
                    <div class="session-item <?php echo $selected_session == $session['id'] ? 'active' : ''; ?>" 
                         onclick="window.location.href='admin_chat.php?session=<?php echo $session['id']; ?>'">
                        <div class="session-name">
                            <i class="fas fa-user-circle"></i>
                            <?php echo htmlspecialchars($session['visitor_name'] ?? 'Guest User'); ?>
                        </div>
                        <div class="session-info">
                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($session['visitor_email'] ?? 'No email'); ?>
                        </div>
                        <div class="session-time">
                            <i class="far fa-clock"></i> Started: <?php echo date('h:i A', strtotime($session['started_at'])); ?>
                        </div>
                        <?php if ($session['unread_count'] > 0): ?>
                            <div class="unread-badge">
                                <?php echo $session['unread_count']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="padding: 30px; text-align: center; color: #aaa;">
                    <i class="fas fa-comment-slash" style="font-size: 40px; margin-bottom: 10px; display: block;"></i>
                    No active chat sessions
                </div>
            <?php endif; ?>
        </div>

        <div class="chat-main">
            <?php if ($selected_session > 0): ?>
                <?php
                $session_detail = $conn->query("SELECT * FROM chat_sessions WHERE id = $selected_session")->fetch_assoc();
                ?>
                <div class="chat-header">
                    <h3>
                        <i class="fas fa-circle" style="color: #2ecc71; font-size: 10px;"></i>
                        Chat with: <?php echo htmlspecialchars($session_detail['visitor_name'] ?? 'Guest User'); ?>
                    </h3>
                    <p>
                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($session_detail['visitor_email'] ?? 'No email provided'); ?>
                    </p>
                </div>
                <div class="chat-messages" id="chatMessages">
                    <div style="text-align: center; padding: 30px; color: #aaa;">
                        <i class="fas fa-spinner fa-pulse"></i> Loading messages...
                    </div>
                </div>
                <div class="typing-indicator" id="typingIndicator" style="display: none;">
                    <i class="fas fa-ellipsis-h"></i> Patient is typing...
                </div>
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Type your reply here..." autocomplete="off">
                    <button onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
            <?php else: ?>
                <div class="no-session">
                    <i class="fas fa-comments"></i>
                    <p>Select a chat session from the left to start messaging</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($selected_session > 0): ?>
    <script>
        let sessionId = <?php echo $selected_session; ?>;
        let lastMessageId = 0;
        let isSending = false;
        let lastTypingTime = 0;

        function loadMessages() {
            fetch(`chat_ajax.php?action=get_messages&session_id=${sessionId}&last_id=${lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        const messagesDiv = document.getElementById('chatMessages');
                        if (lastMessageId === 0) messagesDiv.innerHTML = '';
                        data.messages.forEach(msg => addMessageToDOM(msg));
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    }
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        function addMessageToDOM(msg) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${msg.sender_type}`;
            if (msg.sender_type === 'user' && !msg.is_read) messageDiv.classList.add('unread');
            
            const senderIcon = msg.sender_type === 'user' ? '👤' : '👨‍⚕️';
            const unreadIndicator = (msg.sender_type === 'user' && !msg.is_read) ? '<span style="color: #e74c3c; margin-left: 8px;">● New</span>' : '';
            
            messageDiv.innerHTML = `
                <div class="message-sender">${senderIcon} ${msg.sender_name} ${unreadIndicator}</div>
                <div class="message-bubble">${escapeHtml(msg.message)}</div>
                <div class="message-time">${msg.time}</div>
                ${msg.sender_type === 'user' && !msg.is_read ? `<button class="mark-read-btn" onclick="markAsRead(${msg.id})">✓ Mark as Read</button>` : ''}
            `;
            messagesDiv.appendChild(messageDiv);
        }

        function markAsRead(messageId) {
            fetch('admin_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `mark_read=1&session_id=${sessionId}&message_id=${messageId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) loadMessages();
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function sendMessage() {
            if (isSending) return;
            
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            if (!message) return;
            
            isSending = true;
            input.disabled = true;
            const sendBtn = document.querySelector('.chat-input button');
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Sending...';
            }
            
            fetch('chat_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=send_message&session_id=${sessionId}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    loadMessages();
                } else {
                    alert('Failed to send message: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => console.error('Error sending message:', error))
            .finally(() => {
                isSending = false;
                input.disabled = false;
                if (sendBtn) {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
                }
                input.focus();
            });
        }

        function sendTyping() {
            const now = Date.now();
            if (now - lastTypingTime < 3000) return;
            lastTypingTime = now;
            
            fetch('chat_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=typing&session_id=${sessionId}`
            }).catch(error => console.error('Error sending typing:', error));
        }

        function checkTyping() {
            fetch(`chat_ajax.php?action=check_typing&session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    const indicator = document.getElementById('typingIndicator');
                    indicator.style.display = data.typing ? 'flex' : 'none';
                });
        }

        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            } else {
                sendTyping();
            }
        });

        loadMessages();
        setInterval(() => { loadMessages(); checkTyping(); }, 3000);
    </script>
    <?php endif; ?>
</body>
</html>