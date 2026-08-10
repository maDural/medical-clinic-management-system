<?php
/**
 * Logout Script - Florida Medical Clinic
 * Securely destroys user session and redirects to home page
 */

session_start();

// Initialize logout message
$logout_message = "";

// Check if user was logged in to show appropriate message
$was_logged_in = isset($_SESSION['user_id']) || isset($_SESSION['admin_logged_in']);

// Store user info for goodbye message (optional)
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

/* 🔐 Destroy all session data */
$_SESSION = array();

/* 🧹 Remove session cookie (extra security) */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* 🛑 Destroy session */
session_destroy();

/* 📝 Log logout activity (optional - uncomment if you have a logs table) */
/*
if ($was_logged_in && isset($_SERVER['REMOTE_ADDR'])) {
    // Log logout to database (optional)
    // $log_query = "INSERT INTO user_logs (user_id, action, ip_address, timestamp) VALUES (?, 'logout', ?, NOW())";
}
*/

// Set success message for display
$logout_success = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Logout - Florida Medical Clinic</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: url('dash.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Overlay for better readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
            z-index: 0;
        }

        .logout-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            margin: 20px;
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

        /* Brand Section */
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 20px 40px rgba(102, 126, 234, 0.6);
            }
        }

        .brand-icon i {
            font-size: 38px;
            color: white;
        }

        .brand h2 {
            color: white;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Form Box */
        .logout-box {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px;
            padding: 45px 40px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .success-icon i {
            font-size: 45px;
            color: white;
        }

        .logout-box h2 {
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .logout-message {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .btn-primary {
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            padding: 14px 32px;
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
        }

        .divider span {
            background: white;
            padding: 0 20px;
            position: relative;
            color: #999;
            font-size: 12px;
        }

        /* Footer Links */
        .footer-links {
            margin-top: 20px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-links a:hover {
            color: #764ba2;
            gap: 10px;
        }

        /* Footer Note */
        .footer-note {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }

        .footer-note i {
            margin: 0 4px;
        }

        /* Countdown Timer */
        .countdown {
            margin-top: 20px;
            font-size: 13px;
            color: #999;
        }

        .countdown span {
            font-weight: 700;
            color: #667eea;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .logout-box {
                padding: 35px 25px;
            }
            
            .success-icon {
                width: 70px;
                height: 70px;
            }
            
            .success-icon i {
                font-size: 35px;
            }
            
            .logout-box h2 {
                font-size: 24px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary {
                justify-content: center;
            }
        }
    </style>
    <script>
        // Auto-redirect countdown
        let seconds = 5;
        function updateCountdown() {
            const countdownElement = document.getElementById('countdown');
            if (countdownElement && seconds > 0) {
                countdownElement.innerHTML = `You will be redirected to <span>${seconds}</span> seconds...`;
                seconds--;
                setTimeout(updateCountdown, 1000);
            }
        }
        // Start countdown when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateCountdown();
            // Redirect after 5 seconds
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 5000);
        });
    </script>
</head>
<body>

<div class="logout-container">
    <!-- Brand Section -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <h2>Florida Medical Clinic</h2>
    </div>

    <!-- Logout Box -->
    <div class="logout-box">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h2>You've Been Logged Out</h2>
        <div class="logout-message">
            Thank you for visiting Florida Medical Clinic.<br>
            Your session has been securely terminated.
        </div>

        <div class="button-group">
            <a href="login.php" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Sign In Again
            </a>
            <a href="index.php" class="btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>

        <div class="divider">
            <span>Need help?</span>
        </div>

        <div class="footer-links">
            <a href="contact.php">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>

        <div class="countdown" id="countdown">
            Redirecting to <span>5</span> seconds...
        </div>
    </div>

    <div class="footer-note">
        <i class="fas fa-shield-alt"></i> Secure Logout | <i class="fas fa-lock"></i> Session Terminated
    </div>
</div>

</body>
</html>