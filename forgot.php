<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if (isset($_POST['forgot'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $query = "SELECT email, fullname FROM users WHERE email='$email'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['reset_email'] = $email;
        
        // Optional: Send email notification
        // mail($email, "Password Reset Request", "Click the link to reset your password: " . $_SERVER['HTTP_HOST'] . "/reset_password.php");
        
        $success = "Password reset link sent! Redirecting to reset page...";
        header("refresh:2;url=reset_password.php");
        exit();
    } else {
        $error = "Email not found in our records";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Florida Medical Clinic</title>
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

        .forgot-container {
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
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .brand p {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
        }

        /* Form Box */
        .form-box {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }

        .form-box h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Input Group */
        .input-group {
            margin-bottom: 25px;
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
            border: 2px solid #eef2f6;
            border-radius: 14px;
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

        input::placeholder {
            color: #bbb;
            font-weight: 400;
        }

        /* Button */
        .reset-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 14px;
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

        /* Error Message */
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-left: 4px solid #dc2626;
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-left: 4px solid #28a745;
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
            .form-box {
                padding: 30px 25px;
            }
            
            .form-box h2 {
                font-size: 24px;
            }
            
            .brand-icon {
                width: 55px;
                height: 55px;
            }
            
            .brand-icon i {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<div class="forgot-container">
    <!-- Brand Section -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-key"></i>
        </div>
        <h2>Florida Medical Clinic</h2>
        <p>Password Recovery</p>
    </div>

    <!-- Form Box -->
    <div class="form-box">
        <h2>Forgot Password? 🔐</h2>
        <div class="form-subtitle">Enter your email to reset your password</div>

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
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
            </div>

            <button type="submit" name="forgot" class="reset-btn">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <div class="back-link">
            <a href="login.php">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>

        <div class="footer-note">
            <i class="fas fa-lock"></i> We'll send a password reset link to your email
        </div>
    </div>
</div>

</body>
</html>