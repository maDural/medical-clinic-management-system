<?php
session_start();
include 'includes/db.php';

// If admin is logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

// If user is logged in as patient, use their info
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'] ?? '';
    $user_email = $_SESSION['user_email'] ?? '';
    
    // Get user details if not in session
    if (empty($user_name) || empty($user_email)) {
        $user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
        if ($user_query && $user_query->num_rows > 0) {
            $user = $user_query->fetch_assoc();
            $user_name = $user['fullname'];
            $user_email = $user['email'];
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
        }
    }
} 
// If visitor came from pre-chat form
elseif (isset($_SESSION['chat_visitor_temp'])) {
    $user_name = $_SESSION['chat_visitor_name'];
    $user_email = $_SESSION['chat_visitor_email'];
    $user_id = null;
} 
// If no user info, redirect to pre-chat
else {
    header("Location: pre_chat.php");
    exit();
}

// Generate or retrieve session token
if (!isset($_SESSION['chat_token'])) {
    $_SESSION['chat_token'] = bin2hex(random_bytes(16));
}
$session_token = $_SESSION['chat_token'];

// Check if active session exists, create if not
$check_session = $conn->query("SELECT id FROM chat_sessions WHERE session_token = '$session_token' AND status != 'closed'");
if ($check_session->num_rows == 0) {
    $user_id_val = isset($user_id) && $user_id ? $user_id : 'NULL';
    $conn->query("INSERT INTO chat_sessions (user_id, session_token, visitor_name, visitor_email, status, started_at) 
                  VALUES ($user_id_val, '$session_token', '$user_name', '$user_email', 'active', NOW())");
}

// Get session ID
$session_result = $conn->query("SELECT id FROM chat_sessions WHERE session_token = '$session_token' AND status != 'closed'");
$session_row = $session_result->fetch_assoc();
$session_id = $session_row['id'];

// Clear temporary visitor flag
if (isset($_SESSION['chat_visitor_temp'])) {
    unset($_SESSION['chat_visitor_temp']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Support - Florida Medical Clinic</title>
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
            /* Professional medical background with gradient overlay */
            background: linear-gradient(135deg, rgba(26, 41, 128, 0.85) 0%, rgba(38, 208, 206, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }
        
        /* Decorative background element */
        body::before {
            content: '';
            position: fixed;
            bottom: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

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

        .chat-container {
            max-width: 1000px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            height: calc(100vh - 110px);
            display: flex;
            flex-direction: column;
            animation: fadeInUp 0.5s ease;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .chat-header h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .chat-header p {
            font-size: 13px;
            opacity: 0.9;
        }

        .online-status {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #2ecc71;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 5px #2ecc71;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            background: rgba(248, 250, 252, 0.95);
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #eef2f6;
            border-radius: 10px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .message {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            animation: messageFadeIn 0.3s ease;
        }

        @keyframes messageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.user {
            align-items: flex-end;
        }

        .message.admin {
            align-items: flex-start;
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 20px;
            position: relative;
            word-wrap: break-word;
            line-height: 1.5;
            font-size: 14px;
        }

        .message.user .message-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
        }

        .message.admin .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #eef2f6;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .message-sender {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
            padding-left: 8px;
            color: #888;
        }

        .message-time {
            font-size: 10px;
            margin-top: 5px;
            padding-left: 8px;
            color: #aaa;
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

        .typing-indicator i {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .chat-input-area {
            padding: 20px 25px;
            background: white;
            border-top: 1px solid #eef2f6;
            display: flex;
            gap: 12px;
        }

        .chat-input-area input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #eef2f6;
            border-radius: 30px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .chat-input-area input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .chat-input-area button {
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-input-area button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .no-messages {
            text-align: center;
            padding: 60px;
            color: #aaa;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .no-messages i {
            font-size: 64px;
            color: #ddd;
        }

        .visitor-info {
            background: rgba(248, 249, 250, 0.95);
            padding: 10px 20px;
            font-size: 12px;
            color: #666;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .visitor-info span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .visitor-info i {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .chat-container {
                margin: 10px;
                height: calc(100vh - 80px);
                border-radius: 20px;
            }
            .message-bubble { max-width: 85%; }
            .top-bar { padding: 12px 20px; }
            .top-bar h2 { font-size: 18px; }
            .back-btn, .logout-btn { padding: 6px 14px; font-size: 12px; }
            .chat-messages { padding: 15px; }
            .chat-input-area { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>
        <i class="fas fa-comments"></i>
        Live Chat Support
    </h2>
    <div class="top-bar-buttons">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="chat-container">
    <div class="chat-header">
        <h3>
            <span class="online-status"></span>
            Florida Medical Clinic Support
        </h3>
        <p><i class="fas fa-clock"></i> We typically reply within a few minutes</p>
    </div>

    <div class="visitor-info">
        <span><i class="fas fa-user"></i> Chatting as: <?php echo htmlspecialchars($user_name); ?></span>
        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user_email); ?></span>
    </div>

    <div class="chat-messages" id="chatMessages">
        <div class="no-messages">
            <i class="fas fa-comment-dots"></i>
            <p>Start a conversation with our support team</p>
            <small>Type your message below to get started</small>
        </div>
    </div>

    <div class="typing-indicator" id="typingIndicator" style="display: none;">
        <i class="fas fa-ellipsis-h"></i> Admin is typing...
    </div>

    <div class="chat-input-area">
        <input type="text" id="messageInput" placeholder="Type your message here..." autocomplete="off">
        <button onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i> Send
        </button>
    </div>
</div>

<script>
    let sessionId = <?php echo $session_id; ?>;
    let lastMessageId = 0;
    let isSending = false;
    let lastTypingTime = 0;

    function loadMessages() {
        fetch(`chat_ajax.php?action=get_messages&session_id=${sessionId}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    const messagesDiv = document.getElementById('chatMessages');
                    const noMsg = messagesDiv.querySelector('.no-messages');
                    if (noMsg && lastMessageId === 0) noMsg.remove();
                    
                    if (lastMessageId === 0 && data.messages.length > 0) messagesDiv.innerHTML = '';
                    
                    data.messages.forEach(msg => {
                        addMessageToDOM(msg);
                        if (msg.id > lastMessageId) lastMessageId = msg.id;
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }

    function addMessageToDOM(msg) {
        const messagesDiv = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${msg.sender_type}`;
        
        const senderName = msg.sender_type === 'user' ? 'You' : (msg.sender_name || 'Support Team');
        const senderIcon = msg.sender_type === 'user' ? '<i class="fas fa-user-circle"></i>' : '<i class="fas fa-headset"></i>';
        
        messageDiv.innerHTML = `
            <div class="message-sender">${senderIcon} ${senderName}</div>
            <div class="message-bubble">${escapeHtml(msg.message)}</div>
            <div class="message-time"><i class="far fa-clock"></i> ${msg.time}</div>
        `;
        messagesDiv.appendChild(messageDiv);
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
        const sendBtn = document.querySelector('.chat-input-area button');
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
                alert(data.error || 'Failed to send message');
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
            })
            .catch(error => console.error('Error checking typing:', error));
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
    setInterval(() => {
        loadMessages();
        checkTyping();
    }, 3000);
</script>

</body>
</html>