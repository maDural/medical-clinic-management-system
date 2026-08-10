<?php
session_start();
include 'includes/db.php';

// Redirect if already logged in as admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';
$show_forgot = false;

if (isset($_POST['admin_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Check admin credentials
    if ($username === 'Ma Dural' && $password === 'Mthetheleli123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Florida Medical Clinic</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-image: url('adminDash.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        /* Dark overlay for better readability */
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
        
        .login-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.98);
            padding: 45px 40px;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.6s ease;
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
        
        /* Logo/Brand Section */
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .brand-icon i {
            font-size: 32px;
            color: white;
        }
        
        .brand h2 {
            color: #1a1a2e;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .brand p {
            color: #666;
            font-size: 13px;
        }
        
        /* Form Title */
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-title h3 {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-title p {
            color: #888;
            font-size: 13px;
        }
        
        /* Input Groups */
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .input-group label i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
        }
        
        input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        input::placeholder {
            color: #bbb;
            font-weight: 400;
        }
        
        /* Options Row */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #666;
        }
        
        .remember-me input {
            width: auto;
            padding: 0;
            margin: 0;
            accent-color: #667eea;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
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
            background: #e0e0e0;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 12px;
        }
        
        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 20px;
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
            color: #764ba2;
            gap: 10px;
        }
        
        /* Error Message */
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-left: 3px solid #dc2626;
        }
        
        /* Footer Note */
        .footer-note {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: #999;
        }
        
        .footer-note i {
            color: #667eea;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .brand-icon {
                width: 55px;
                height: 55px;
            }
            
            .brand-icon i {
                font-size: 26px;
            }
            
            .brand h2 {
                font-size: 20px;
            }
            
            .form-title h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Brand Section -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Florida Medical Clinic</h2>
            <p>Administrative Portal</p>
        </div>
        
        <div class="form-title">
            <h3>Welcome Back, Admin</h3>
            <p>Please sign in to continue</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label><i class="fas fa-user"></i> Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" name="username" placeholder="Enter your username" required>
                </div>
            </div>
            
            <div class="input-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            
            <div class="options-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            
            <button type="submit" name="admin_login" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div class="divider">
            <span>Secure Access Only</span>
        </div>
        
        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to Homepage
            </a>
        </div>
        
        <div class="footer-note">
            <i class="fas fa-lock"></i> Secure Login | <i class="fas fa-shield-alt"></i> Protected Area
        </div>
    </div>
</body>
</html>