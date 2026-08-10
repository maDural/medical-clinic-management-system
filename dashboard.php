<?php
session_start();
include 'includes/db.php';

// protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];
$user_email = $user['email'];

// Get unread chat messages count
$unread_count = 0;
$chat_session = $conn->query("SELECT id FROM chat_sessions WHERE user_id = '$user_id' AND status = 'active'");
if ($chat_session && $chat_session->num_rows > 0) {
    $session = $chat_session->fetch_assoc();
    $unread_query = "SELECT COUNT(*) as count FROM chat_messages WHERE session_id = '{$session['id']}' AND sender_type = 'admin' AND is_read = 0";
    $unread_result = $conn->query($unread_query);
    if ($unread_result) {
        $unread = $unread_result->fetch_assoc();
        $unread_count = $unread['count'];
    }
}

// Check if user is being impersonated (Patient View Mode)
$is_patient_view_mode = isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Florida Medical Clinic</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    
    <style>
        /* Dashboard specific styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('dash.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        
        /* Dark overlay for better text readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        
        /* Patient View Mode Banner */
        .patient-view-banner {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 12px 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1001;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .patient-view-banner i {
            font-size: 18px;
        }
        
        .exit-patient-view-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 6px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .exit-patient-view-btn:hover {
            background: white;
            color: #ff5722;
            transform: translateY(-2px);
        }
        
        .top-bar {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .top-bar h2 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logout-btn {
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 80px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 28px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            backdrop-filter: blur(5px);
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dashboard-container h1 {
            color: #1a1a2e;
            margin-bottom: 10px;
            font-size: 36px;
            font-weight: 800;
        }
        
        .welcome-text {
            color: #667eea;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .dashboard-container p {
            color: #666;
            margin-bottom: 40px;
            font-size: 18px;
        }
        
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 40px 50px;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-width: 220px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        .card-icon {
            font-size: 48px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 700;
        }
        
        .card-desc {
            font-size: 12px;
            opacity: 0.8;
            font-weight: normal;
        }
        
        /* Unread badge */
        .unread-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            min-width: 22px;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Different colors for each card */
        .card:nth-child(1) {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card:nth-child(2) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .card:nth-child(3) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .card:nth-child(4) {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .card:nth-child(5) {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        /* Floating Chat Widget */
        .chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .chat-toggle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .chat-toggle:hover {
            transform: scale(1.1);
        }
        
        .chat-toggle-icon {
            font-size: 28px;
            color: white;
        }
        
        .chat-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            animation: pulse 1s infinite;
        }
        
        .chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.3s ease;
            z-index: 1000;
        }
        
        .chat-window.show {
            display: flex;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .chat-window-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chat-window-header h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .chat-messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .chat-message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
        
        .chat-message.user {
            align-items: flex-end;
        }
        
        .chat-message.admin {
            align-items: flex-start;
        }
        
        .chat-bubble {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 13px;
        }
        
        .chat-message.user .chat-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .chat-message.admin .chat-bubble {
            background: #f0f0f0;
            color: #333;
            border-bottom-left-radius: 4px;
        }
        
        .chat-time {
            font-size: 10px;
            color: #999;
            margin-top: 4px;
            padding-left: 5px;
        }
        
        .chat-input-area {
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            background: white;
        }
        
        .chat-input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 13px;
        }
        
        .chat-input-area input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .chat-input-area button {
            padding: 10px 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }
        
        .typing-indicator {
            padding: 5px 15px;
            font-size: 11px;
            color: #999;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 40px 20px;
                padding: 30px;
            }
            
            .dashboard-container h1 {
                font-size: 28px;
            }
            
            .card {
                padding: 30px 40px;
                min-width: 180px;
            }
            
            .card-icon {
                font-size: 36px;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-container {
                margin: 20px 15px;
                padding: 20px;
            }
            
            .dashboard-container h1 {
                font-size: 24px;
            }
            
            .card {
                padding: 20px 25px;
                min-width: 100%;
            }
            
            .chat-window {
                width: calc(100% - 40px);
                right: 20px;
                bottom: 80px;
                height: 450px;
            }
            
            .chat-toggle {
                width: 50px;
                height: 50px;
            }
            
            .chat-toggle-icon {
                font-size: 24px;
            }
            
            .patient-view-banner {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Patient View Mode Banner (when admin is viewing as patient) -->
<?php if ($is_patient_view_mode): ?>
<div class="patient-view-banner">
    <i class="fas fa-eye"></i>
    <strong>Patient View Mode Active</strong> - You are viewing your account as a patient would
    <a href="admin_stop_impersonate.php" class="exit-patient-view-btn">
        <i class="fas fa-sign-out-alt"></i> Exit Patient View
    </a>
</div>
<?php endif; ?>

<!-- TOP BAR -->
<div class="top-bar">
    <h2>
        <i class="fas fa-heartbeat"></i>
        Patient Dashboard
    </h2>
    <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- DASHBOARD CONTENT -->
<div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
    <div class="welcome-text">Manage your healthcare needs from one place</div>
    <p>What would you like to do today?</p>

    <div class="dashboard-cards">
        <!-- BOOK APPOINTMENT CARD -->
        <a href="appointment.php" class="card">
            <div class="card-icon">📅</div>
            <div class="card-title">Book Appointment</div>
            <div class="card-desc">Schedule in-person or telehealth visit</div>
        </a>

        <!-- MY APPOINTMENTS CARD -->
        <a href="my_appointments.php" class="card">
            <div class="card-icon">📋</div>
            <div class="card-title">My Appointments</div>
            <div class="card-desc">View and track your appointments</div>
        </a>

        <!-- REQUEST MEDICAL RECORDS CARD -->
        <a href="request_medical_records.php" class="card">
            <div class="card-icon">📄</div>
            <div class="card-title">Medical Records</div>
            <div class="card-desc">Request your medical records</div>
        </a>

        <!-- MY REQUESTS CARD -->
        <a href="my_requests.php" class="card">
            <div class="card-icon">📬</div>
            <div class="card-title">My Requests</div>
            <div class="card-desc">Track your medical record requests & responses</div>
        </a>

        <!-- MY MESSAGES CARD -->
        <a href="my_messages.php" class="card">
            <div class="card-icon">💬</div>
            <div class="card-title">My Messages</div>
            <div class="card-desc">View responses from our team</div>
            <?php if ($unread_count > 0): ?>
                <span class="unread-badge"><?php echo $unread_count; ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>

<!-- Floating Chat Widget -->
<div class="chat-widget">
    <div class="chat-toggle" onclick="toggleChat()">
        <span class="chat-toggle-icon">💬</span>
        <?php if ($unread_count > 0): ?>
            <span class="chat-badge"><?php echo $unread_count; ?></span>
        <?php endif; ?>
    </div>
    
    <div class="chat-window" id="chatWindow">
        <div class="chat-window-header">
            <h4>💬 Live Support</h4>
            <button class="chat-close" onclick="toggleChat()">×</button>
        </div>
        <div class="chat-messages-container" id="chatMessagesContainer">
            <div style="text-align: center; padding: 20px; color: #999;">
                <i class="fas fa-comment-dots" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                Loading messages...
            </div>
        </div>
        <div class="typing-indicator" id="chatTypingIndicator" style="display: none;">
            <i class="fas fa-ellipsis-h"></i> Admin is typing...
        </div>
        <div class="chat-input-area">
            <input type="text" id="chatMessageInput" placeholder="Type your message..." autocomplete="off">
            <button onclick="sendChatMessage()">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </div>
    </div>
</div>

<script>
    let sessionId = null;
    let lastMessageId = 0;
    let chatInterval = null;
    let currentTyping = false;
    
    // Get or create chat session
    function initChatSession() {
        fetch('chat_init.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=init_session'
        })
        .then(response => response.json())
        .then(data => {
            if (data.session_id) {
                sessionId = data.session_id;
                startPolling();
            }
        })
        .catch(error => console.error('Error initializing chat:', error));
    }
    
    function startPolling() {
        if (chatInterval) clearInterval(chatInterval);
        loadChatMessages();
        chatInterval = setInterval(() => {
            loadChatMessages();
            checkAdminTyping();
        }, 3000);
    }
    
    function loadChatMessages() {
        if (!sessionId) return;
        
        fetch(`chat_ajax.php?action=get_messages&session_id=${sessionId}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    const container = document.getElementById('chatMessagesContainer');
                    
                    // Remove loading message
                    if (lastMessageId === 0 && container.innerHTML.includes('Loading messages')) {
                        container.innerHTML = '';
                    }
                    
                    data.messages.forEach(msg => {
                        addChatMessageToDOM(msg);
                        if (msg.id > lastMessageId) lastMessageId = msg.id;
                    });
                    
                    // Update unread badge
                    if (data.unread_count !== undefined) {
                        updateUnreadBadge(data.unread_count);
                    }
                    
                    container.scrollTop = container.scrollHeight;
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }
    
    function addChatMessageToDOM(msg) {
        const container = document.getElementById('chatMessagesContainer');
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${msg.sender_type}`;
        
        const senderIcon = msg.sender_type === 'user' ? '👤' : '👨‍⚕️';
        
        messageDiv.innerHTML = `
            <div class="chat-bubble">${escapeHtml(msg.message)}</div>
            <div class="chat-time">${senderIcon} ${msg.time}</div>
        `;
        
        container.appendChild(messageDiv);
    }
    
    function sendChatMessage() {
        const input = document.getElementById('chatMessageInput');
        const message = input.value.trim();
        
        if (!message || !sessionId) return;
        
        fetch('chat_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=send_message&session_id=${sessionId}&message=${encodeURIComponent(message)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                loadChatMessages();
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }
    
    function checkAdminTyping() {
        if (!sessionId) return;
        
        fetch(`chat_ajax.php?action=check_typing&session_id=${sessionId}`)
            .then(response => response.json())
            .then(data => {
                const indicator = document.getElementById('chatTypingIndicator');
                indicator.style.display = data.typing ? 'flex' : 'none';
            })
            .catch(error => console.error('Error checking typing:', error));
    }
    
    function sendTyping() {
        if (currentTyping) return;
        currentTyping = true;
        
        fetch('chat_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=typing&session_id=${sessionId}`
        });
        
        setTimeout(() => { currentTyping = false; }, 2000);
    }
    
    function updateUnreadBadge(count) {
        const badges = document.querySelectorAll('.chat-badge, .unread-badge');
        badges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        });
    }
    
    function toggleChat() {
        const window = document.getElementById('chatWindow');
        window.classList.toggle('show');
        if (window.classList.contains('show') && !sessionId) {
            initChatSession();
        }
        if (window.classList.contains('show')) {
            setTimeout(() => {
                const container = document.getElementById('chatMessagesContainer');
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Event listeners for typing
    document.getElementById('chatMessageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendChatMessage();
        } else {
            sendTyping();
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initChatSession();
    });
</script>

</body>
</html>