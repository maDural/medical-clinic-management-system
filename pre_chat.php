<?php
session_start();
include 'includes/db.php';

// If user is already logged in, redirect directly to chat
if (isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit();
}

$error = '';

if (isset($_POST['start_chat'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($name) || empty($email)) {
        $error = "Please fill in both name and email fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Store visitor info in session
        $_SESSION['chat_visitor_name'] = $name;
        $_SESSION['chat_visitor_email'] = $email;
        $_SESSION['chat_visitor_temp'] = true;
        
        // Redirect to chat
        header("Location: chat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Chat - Florida Medical Clinic</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
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

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .chat-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            padding: 45px 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            animation: fadeInUp 0.5s ease;
            backdrop-filter: blur(10px);
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

        .chat-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .chat-icon:hover {
            transform: scale(1.05);
        }

        .chat-icon i {
            font-size: 36px;
            color: white;
        }

        h1 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 13px;
        }

        label i {
            margin-right: 8px;
            color: #667eea;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #bbb;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-left: 3px solid #dc2626;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .start-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .start-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            gap: 10px;
            color: #764ba2;
        }

        .info-note {
            background: rgba(248, 249, 250, 0.9);
            padding: 12px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 11px;
            color: #888;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .info-note i {
            color: #28a745;
        }

        @media (max-width: 550px) {
            .chat-card {
                padding: 30px 25px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .chat-icon {
                width: 65px;
                height: 65px;
            }
            
            .chat-icon i {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-card">
            <div class="chat-icon">
                <i class="fas fa-comment-dots"></i>
            </div>
            
            <h1>Chat with us</h1>
            <div class="subtitle">We can't wait to talk to you. But first, please take a couple of moments to tell us a bit about yourself.</div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="Enter your email address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <button type="submit" name="start_chat" class="start-btn">
                    <i class="fas fa-comment"></i> Start Chat
                </button>
            </form>
            
            <div class="back-link">
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i> Back to Homepage
                </a>
            </div>
            
            <div class="info-note">
                <i class="fas fa-lock"></i>
                Your information is secure and will only be used for this chat session.
            </div>
        </div>
    </div>
</body>
</html>