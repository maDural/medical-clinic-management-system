<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

// Check if email already exists
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email already registered
        $check_email = "SELECT email FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $query = "INSERT INTO users (fullname, email, password) VALUES ('$name', '$email', '$hashed_password')";
            
            if ($conn->query($query)) {
                $success = "Account created successfully! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <title>Register | Florida Medical Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* ---------- reset & base ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('booking.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* sophisticated overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(145deg, rgba(10, 10, 30, 0.75) 0%, rgba(30, 20, 50, 0.60) 100%);
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        /* ---------- container ---------- */
        .register-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            margin: 24px;
            animation: floatUp 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
        }

        @keyframes floatUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* ---------- brand ---------- */
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(145deg, #7c8cf8, #5b6ad0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 20px 35px -8px rgba(90, 110, 230, 0.5);
            transition: transform 0.25s ease, box-shadow 0.3s ease;
        }
        .brand-icon:hover {
            transform: scale(1.04);
            box-shadow: 0 25px 45px -8px rgba(90, 110, 230, 0.7);
        }
        .brand-icon i {
            font-size: 40px;
            color: #fff;
        }
        .brand h2 {
            color: #fff;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: -0.4px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .brand p {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
            font-size: 14px;
            letter-spacing: 0.2px;
        }

        /* ---------- card ---------- */
        .card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(8px);
            border-radius: 32px;
            padding: 44px 38px 36px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
        }

        .card-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .card-header h3 {
            font-size: 30px;
            font-weight: 800;
            color: #14142b;
            letter-spacing: -0.3px;
        }
        .card-header h3 i {
            color: #5b6ad0;
            margin-right: 6px;
        }
        .card-header .sub {
            color: #7a7a9a;
            font-size: 14px;
            font-weight: 400;
            margin-top: 4px;
        }

        /* ---------- messages ---------- */
        .message {
            padding: 14px 18px;
            border-radius: 18px;
            font-weight: 500;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            border-left: 4px solid transparent;
        }
        .message.error {
            background: #fee9e9;
            color: #b91c1c;
            border-left-color: #b91c1c;
        }
        .message.success {
            background: #e3f5ec;
            color: #0a6b4a;
            border-left-color: #0a6b4a;
        }
        .message i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* ---------- form ---------- */
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1f1f3a;
            margin-bottom: 6px;
            letter-spacing: -0.1px;
        }
        .input-group label i {
            color: #5b6ad0;
            margin-right: 8px;
            width: 18px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0a0bc;
            font-size: 16px;
            transition: color 0.2s;
        }
        .input-wrapper input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 2px solid #eaeef5;
            border-radius: 18px;
            font-size: 14px;
            font-weight: 500;
            background: #f9faff;
            transition: all 0.2s;
            font-family: inherit;
            color: #12122a;
        }
        .input-wrapper input::placeholder {
            color: #b0b0cc;
            font-weight: 400;
        }
        .input-wrapper input:focus {
            outline: none;
            border-color: #5b6ad0;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(91, 106, 208, 0.12);
        }
        .input-wrapper input:focus + i {
            color: #5b6ad0;
        }

        .hint {
            font-size: 11px;
            color: #8888aa;
            margin-top: 6px;
            padding-left: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .hint i {
            color: #5b6ad0;
            font-size: 11px;
        }

        /* ---------- button ---------- */
        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(145deg, #5b6ad0, #4452b0);
            color: #fff;
            border: none;
            border-radius: 24px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.25s ease;
            margin-top: 6px;
            box-shadow: 0 8px 20px -6px rgba(70, 90, 200, 0.45);
        }
        .btn-primary i {
            font-size: 18px;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -8px rgba(70, 90, 200, 0.6);
            background: linear-gradient(145deg, #4f5fc9, #3848a8);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 6px 14px -4px rgba(70, 90, 200, 0.5);
        }

        /* ---------- divider ---------- */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0 20px;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #d0d5e0, transparent);
        }
        .divider-text {
            font-size: 12px;
            font-weight: 500;
            color: #9a9ab0;
            white-space: nowrap;
        }

        /* ---------- secondary actions ---------- */
        .action-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            background: transparent;
            color: #5b6ad0;
            border-color: #d0d8f0;
            margin-bottom: 12px;
        }
        .action-link i {
            font-size: 16px;
        }
        .action-link:hover {
            background: #f0f4ff;
            border-color: #5b6ad0;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -6px rgba(70, 90, 200, 0.2);
        }
        .action-link.secondary {
            color: #6f6f8a;
            border-color: #e5e9f0;
        }
        .action-link.secondary:hover {
            background: #f5f7fc;
            border-color: #b0b8d0;
            color: #3d3d5c;
            box-shadow: none;
        }

        /* ---------- footer ---------- */
        .footer-note {
            text-align: center;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #eaeef5;
            font-size: 12px;
            color: #8e8eaa;
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .footer-note i {
            color: #5b6ad0;
            margin-right: 4px;
        }
        .footer-note span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 500px) {
            .card {
                padding: 32px 22px 28px;
            }
            .card-header h3 {
                font-size: 26px;
            }
            .brand h2 {
                font-size: 24px;
            }
        }
        @media (max-width: 400px) {
            .register-wrapper {
                margin: 12px;
            }
            .card {
                padding: 26px 16px 22px;
                border-radius: 26px;
            }
            .input-wrapper input {
                padding: 14px 14px 14px 44px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <!-- brand -->
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>Florida Medical Clinic</h2>
        <p>Compassionate care, modern convenience</p>
    </div>

    <!-- main card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-pen-fancy"></i> Create Account</h3>
            <div class="sub">Join our patient family in seconds</div>
        </div>

        <!-- messages -->
        <?php if (!empty($error)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- form -->
        <form method="POST" action="" id="registerForm">
            <div class="input-group">
                <label><i class="fas fa-user"></i> Full name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" name="name" placeholder="e.g. James Rodriguez" required autocomplete="name" />
                </div>
            </div>

            <div class="input-group">
                <label><i class="fas fa-envelope"></i> Email address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="you@example.com" required autocomplete="email" />
                </div>
            </div>

            <div class="input-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" id="password" placeholder="Min. 6 characters" required autocomplete="new-password" />
                </div>
                <div class="hint">
                    <i class="fas fa-info-circle"></i> Must be at least 6 characters
                </div>
            </div>

            <div class="input-group">
                <label><i class="fas fa-check-circle"></i> Confirm password</label>
                <div class="input-wrapper">
                    <i class="fas fa-check-double"></i>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required autocomplete="new-password" />
                </div>
            </div>

            <button type="submit" name="register" class="btn-primary">
                <i class="fas fa-user-plus"></i> Register now
            </button>
        </form>

        <!-- divider -->
        <div class="divider">
            <span class="divider-line"></span>
            <span class="divider-text">already registered?</span>
            <span class="divider-line"></span>
        </div>

        <!-- action links -->
        <a href="login.php" class="action-link">
            <i class="fas fa-sign-in-alt"></i> Log in to your account
        </a>

        <a href="index.php" class="action-link secondary">
            <i class="fas fa-arrow-left"></i> Back to homepage
        </a>

        <!-- footer -->
        <div class="footer-note">
            <span><i class="fas fa-shield-alt"></i> Secure &amp; encrypted</span>
            <span><i class="fas fa-lock"></i> HIPAA compliant</span>
            <span><i class="fas fa-heartbeat"></i> Patient-first</span>
        </div>
    </div>
</div>

<!-- client-side validation (mirrors server logic) -->
<script>
    (function() {
        const form = document.getElementById('registerForm');
        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('confirm_password');

        form.addEventListener('submit', function(e) {
            const password = pwd.value.trim();
            const confirm = confirmPwd.value.trim();

            if (password !== confirm) {
                e.preventDefault();
                alert('✗ Passwords do not match. Please re-enter.');
                confirmPwd.focus();
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('✗ Password must be at least 6 characters long.');
                pwd.focus();
                return;
            }
        });
    })();
</script>

<!-- dummy PHP logic (kept for dynamic message rendering) -->
<?php
// This block ensures the PHP variables are defined even if the form isn't submitted.
// It also simulates the registration logic from the original code.
if (!isset($error)) $error = '';
if (!isset($success)) $success = '';
?>
</body>
</html>