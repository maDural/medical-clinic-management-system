<?php
session_start();
include 'includes/db.php';

$success = '';
$error = '';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$user_email = '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
    if ($user_query && $user_query->num_rows > 0) {
        $user = $user_query->fetch_assoc();
        $user_name = $user['fullname'];
        $user_email = $user['email'];
    }
}

if (isset($_POST['submit_feedback'])) {
    $user_id = isset($_SESSION['user_id']) && $_SESSION['user_id'] ? $_SESSION['user_id'] : NULL;
    
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $feedback_type = mysqli_real_escape_string($conn, $_POST['feedback_type']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    if ($user_id === NULL || $user_id === '') {
        $query = "INSERT INTO feedback (user_id, fullname, email, phone, feedback_type, message, status, created_at) 
                  VALUES (NULL, '$fullname', '$email', '$phone', '$feedback_type', '$message', 'pending', NOW())";
    } else {
        $query = "INSERT INTO feedback (user_id, fullname, email, phone, feedback_type, message, status, created_at) 
                  VALUES ('$user_id', '$fullname', '$email', '$phone', '$feedback_type', '$message', 'pending', NOW())";
    }
    
    if ($conn->query($query)) {
        $success = "✅ Thank you for your feedback! We will review it and respond within 2-3 business days.";
        $_POST = array();
    } else {
        $error = "❌ Error submitting feedback: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - Florida Medical Clinic</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }

        /* Form Box */
        .form-box {
            background: white;
            border-radius: 28px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 580px;
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
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

        .form-inner {
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .welcome {
            text-align: center;
            color: #667eea;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        /* Messages */
        .success, .error {
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Form Groups */
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

        .required {
            color: #e74c3c;
        }

        select, input, textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Info Note */
        .info-note {
            background: #f8f9fa;
            padding: 16px 18px;
            border-radius: 16px;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .info-note strong {
            color: #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-inner {
                padding: 30px 25px;
            }

            h2 {
                font-size: 24px;
            }

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
        }

        @media (max-width: 480px) {
            .form-inner {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>
        <i class="fas fa-comment-dots"></i>
        Feedback & Assistance
    </h2>
    <div class="top-bar-buttons">
        <a href="index.php" class="back-btn">
            <i class="fas fa-home"></i> Home
        </a>
        <?php if ($is_logged_in): ?>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="form-box">
        <div class="form-inner">
            <h2>We Value Your Feedback</h2>
            <div class="subtitle">Your voice helps us improve our services</div>
            
            <?php if ($is_logged_in): ?>
                <div class="welcome">
                    <i class="fas fa-hand-wave"></i> Welcome back, <?php echo htmlspecialchars($user_name); ?>!
                </div>
            <?php else: ?>
                <div class="welcome">
                    <i class="fas fa-user-friends"></i> You are submitting as a guest
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <input type="text" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : htmlspecialchars($user_name); ?>" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($user_email); ?>" placeholder="Enter your email address" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-phone-alt"></i> Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" placeholder="(555) 123-4567">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Feedback Type <span class="required">*</span></label>
                    <select name="feedback_type" required>
                        <option value="general">💬 General Feedback</option>
                        <option value="accessibility">♿ Accessibility Issue</option>
                        <option value="suggestion">💡 Suggestion</option>
                        <option value="complaint">⚠️ Complaint</option>
                        <option value="assistance">🆘 Request Assistance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Message <span class="required">*</span></label>
                    <textarea name="message" placeholder="Please describe your feedback, issue, or request in detail..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>

                <button type="submit" name="submit_feedback" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Feedback
                </button>

                <div class="info-note">
                    <strong><i class="fas fa-info-circle"></i> What happens next?</strong>
                    We review all feedback within 2-3 business days. You may be contacted if we need clarification.
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>