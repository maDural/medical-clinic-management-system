<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            
            // Remember me functionality
            if (isset($_POST['remember'])) {
                setcookie('user_email', $email, time() + (86400 * 30), "/");
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "No account found with this email address!";
    }
}

// Check for remembered email
$remembered_email = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login - Florida Medical Clinic</title>
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

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
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
            transition: all 0.3s ease;
        }

        .brand-icon:hover {
            transform: scale(1.05);
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
            letter-spacing: -0.5px;
        }

        .brand p {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        /* Form Box */
        .form-box {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px;
            padding: 45px 40px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        .form-box h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 35px;
        }

        /* Input Groups */
        .input-group {
            margin-bottom: 24px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
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
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input {
            width: 100%;
            padding: 15px 15px 15px 48px;
            border: 2px solid #eef2f6;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafbfc;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        input:focus + i {
            color: #667eea;
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
            margin-bottom: 28px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .remember-me:hover {
            color: #667eea;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            padding: 0;
            margin: 0;
            accent-color: #667eea;
            cursor: pointer;
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
            padding: 15px;
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
            margin-bottom: 20px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 25px 0;
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
            font-weight: 500;
        }

        /* Create Account Button */
        .create-account-btn {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .create-account-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Home Button */
        .home-btn {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: #888;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .home-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        /* Error Message */
        .error-message {
            background: #fee2e2;
            color: #dc2626;
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
            border-left: 4px solid #dc2626;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Footer Note */
        .footer-note {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eef2f6;
            font-size: 11px;
            color: #999;
        }

        .footer-note i {
            color: #667eea;
            margin: 0 4px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .form-box {
                padding: 30px 25px;
            }
            
            .form-box h2 {
                font-size: 26px;
            }
            
            .brand-icon {
                width: 65px;
                height: 65px;
            }
            
            .brand-icon i {
                font-size: 30px;
            }
            
            .brand h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Brand Section -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <h2>Florida Medical Clinic</h2>
        <p>Patient Portal Login</p>
    </div>

    <!-- Login Form -->
    <div class="form-box">
        <h2>Welcome Back! 👋</h2>
        <div class="form-subtitle">Please sign in to continue</div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($remembered_email); ?>" required>
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
                    <input type="checkbox" name="remember" <?php echo $remembered_email ? 'checked' : ''; ?>> 
                    Remember me
                </label>
                <a href="forgot.php" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" name="login" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Log In
            </button>
        </form>

        <div class="divider">
            <span>New to our clinic?</span>
        </div>

        <a href="register.php" class="create-account-btn">
            <i class="fas fa-user-plus"></i> Create New Account
        </a>

        <a href="index.php" class="home-btn">
            <i class="fas fa-arrow-left"></i> Back to Homepage
        </a>

        <div class="footer-note">
            <i class="fas fa-shield-alt"></i> Secure Login 
            <i class="fas fa-lock"></i> Encrypted Connection
        </div>
    </div>
</div>

</body>
</html>