<?php
session_start();
include 'includes/db.php';

// If reset_email is not set, redirect to forgot password page
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot.php");
    exit();
}

$error = '';
$success = '';

if (isset($_POST['reset'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password
    if (empty($password)) {
        $error = "Please enter a password";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($conn, $_SESSION['reset_email']);
        
        // Update password in database
        $query = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
        
        if ($conn->query($query)) {
            if ($conn->affected_rows > 0) {
                $success = "Password updated successfully! Redirecting to login...";
                unset($_SESSION['reset_email']);
                header("refresh:2;url=login.php");
            } else {
                $error = "User not found. Please request a new password reset.";
                unset($_SESSION['reset_email']);
            }
        } else {
            $error = "Failed to update password: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Florida Medical Clinic</title>
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
            background: url('sign.webp') no-repeat center center fixed;
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

        .reset-container {
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
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .form-subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Input Groups */
        .input-group {
            margin-bottom: 22px;
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

        /* Password Requirements */
        .password-requirements {
            font-size: 11px;
            color: #888;
            margin-top: -10px;
            margin-bottom: 15px;
            padding-left: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-requirements i {
            color: #ffc107;
        }

        /* Button */
        .reset-btn {
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

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #764ba2;
            gap: 12px;
        }

        /* Messages */
        .error-message, .success-message {
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

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
            animation: shake 0.5s ease;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
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
                font-size: 24px;
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

<div class="reset-container">
    <!-- Brand Section -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-key"></i>
        </div>
        <h2>Florida Medical Clinic</h2>
        <p>Create New Password</p>
    </div>

    <!-- Reset Password Form -->
    <div class="form-box">
        <h2>Reset Password 🔐</h2>
        <div class="form-subtitle">Create a new secure password for your account</div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label><i class="fas fa-lock"></i> New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" id="password" placeholder="Enter new password" required>
                </div>
                <div class="password-requirements">
                    <i class="fas fa-info-circle"></i> Password must be at least 6 characters
                </div>
            </div>

            <div class="input-group">
                <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
                </div>
            </div>

            <button type="submit" name="reset" class="reset-btn">
                <i class="fas fa-sync-alt"></i> Update Password
            </button>
        </form>

        <div class="back-link">
            <a href="login.php">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>

        <div class="footer-note">
            <i class="fas fa-shield-alt"></i> Secure Password Reset 
            <i class="fas fa-lock"></i> Encrypted Connection
        </div>
    </div>
</div>

<script>
    // Password match validation (client-side)
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match!');
        } else if (password.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long!');
        }
    });
</script>

</body>
</html>