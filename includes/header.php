<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Florida Medical Clinic - Quality Healthcare Services</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .main-header.scrolled {
            background: white;
            box-shadow: 0 5px 30px rgba(0,0,0,0.12);
            padding: 5px 0;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            transition: all 0.3s ease;
        }
        
        .main-header.scrolled .header-container {
            padding: 10px 30px;
        }
        
        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.02);
        }
        
        .logo-icon {
            font-size: 38px;
            transition: all 0.3s ease;
        }
        
        .logo h1 {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .logo p {
            font-size: 10px;
            color: #888;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        
        /* Navigation */
        .main-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .main-nav a {
            color: #2c3e50;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 40px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
            position: relative;
        }
        
        .main-nav a:not(.btn-login):not(.btn-register):not(.btn-logout):not(.btn-dashboard)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        
        .main-nav a:not(.btn-login):not(.btn-register):not(.btn-logout):not(.btn-dashboard):hover::after {
            width: 70%;
        }
        
        .main-nav a:hover {
            background: rgba(102, 126, 234, 0.08);
            color: #667eea;
        }
        
        .main-nav a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .main-nav a.active::after {
            display: none;
        }
        
        /* Auth Buttons */
        .auth-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .btn-login {
            padding: 8px 22px;
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 900;
            transition: all 0.3s ease;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-register {
            padding: 8px 22px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 13px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-logout {
            padding: 8px 22px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }
        
        /* Dashboard Link */
        .btn-dashboard {
            padding: 8px 22px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        
        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            font-size: 28px;
            cursor: pointer;
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            color: #667eea;
            transform: scale(1.05);
        }
        
        /* Impersonation Indicator */
        .impersonation-indicator {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        /* ========== HIDDEN ADMIN LINK IN BOTTOM-LEFT CORNER ========== */
        .hidden-admin-container {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 999;
        }
        
        /* Invisible trigger area (40x40px in corner) */
        .admin-trigger-area {
            width: 50px;
            height: 50px;
            position: absolute;
            bottom: 0;
            left: 0;
            cursor: pointer;
            z-index: 10;
        }
        
        /* Hidden admin button - invisible until hover */
        .admin-hidden-btn {
            position: fixed;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.75);
            color: #ff9800;
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: scale(0.9);
            border: 1px solid rgba(255, 152, 0, 0.3);
            letter-spacing: 0.5px;
            pointer-events: none;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Show admin button when hovering over trigger area */
        .admin-trigger-area:hover + .admin-hidden-btn,
        .admin-hidden-btn:hover {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            pointer-events: auto;
        }
        
        .admin-hidden-btn:hover {
            background: #ff9800;
            color: white;
            box-shadow: 0 5px 20px rgba(255, 152, 0, 0.4);
            border-color: transparent;
        }
        
        /* Responsive */
        @media (max-width: 1100px) {
            .header-container {
                padding: 12px 20px;
            }
            .main-nav {
                gap: 5px;
            }
            .main-nav a {
                padding: 6px 14px;
                font-size: 13px;
            }
            .btn-login, .btn-register, .btn-logout, .btn-dashboard {
                padding: 6px 18px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 992px) {
            .mobile-menu-btn {
                display: block;
            }
            .main-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                border-radius: 0 0 24px 24px;
                gap: 12px;
                max-height: 80vh;
                overflow-y: auto;
            }
            .main-nav.show {
                display: flex;
            }
            .auth-buttons {
                margin-left: 0;
                flex-direction: column;
                width: 100%;
            }
            .auth-buttons a {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
            .main-nav a {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                padding: 10px 16px;
            }
            .logo h1 {
                font-size: 16px;
            }
            .logo-icon {
                font-size: 28px;
            }
            .logo p {
                font-size: 8px;
            }
            body {
                padding-top: 65px;
            }
        }
        
        @media (max-width: 480px) {
            .logo h1 {
                font-size: 14px;
            }
            .logo-icon {
                font-size: 24px;
            }
            .btn-login, .btn-register, .btn-logout, .btn-dashboard {
                padding: 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<header class="main-header" id="mainHeader">
    <div class="header-container">
        <a href="index.php" class="logo" style="text-decoration: none;">
            <span class="logo-icon">🏥</span>
            <div>
                <h1>Florida Medical Clinic</h1>
                <p>Excellence in Healthcare</p>
            </div>
        </a>
        
        <div class="mobile-menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        
        <nav class="main-nav" id="mainNav">
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                <i class="fas fa-info-circle"></i> About
            </a>
            <a href="services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="fas fa-stethoscope"></i> Services
            </a>
            <a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Contact
            </a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="btn-dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="btn-register">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true): ?>
                <span class="impersonation-indicator">
                    <i class="fas fa-user-secret"></i> Impersonating
                </span>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Hidden Admin Login Link in Bottom-Left Corner -->
<div class="hidden-admin-container">
    <div class="admin-trigger-area"></div>
    <a href="admin_login.php" class="admin-hidden-btn">
        <i class="fas fa-shield-alt"></i> Admin Login
    </a>
</div>

<script>
    // Sticky header on scroll
    window.addEventListener('scroll', function() {
        const header = document.getElementById('mainHeader');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // Mobile menu toggle
    function toggleMenu() {
        const nav = document.getElementById('mainNav');
        nav.classList.toggle('show');
        
        // Change icon when menu is open
        const btn = document.querySelector('.mobile-menu-btn i');
        if (nav.classList.contains('show')) {
            btn.className = 'fas fa-times';
        } else {
            btn.className = 'fas fa-bars';
        }
    }
    
    // Close menu when clicking a link
    document.querySelectorAll('.main-nav a').forEach(link => {
        link.addEventListener('click', () => {
            const nav = document.getElementById('mainNav');
            nav.classList.remove('show');
            const btn = document.querySelector('.mobile-menu-btn i');
            if (btn) btn.className = 'fas fa-bars';
        });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const nav = document.getElementById('mainNav');
        const menuBtn = document.querySelector('.mobile-menu-btn');
        
        if (nav && nav.classList.contains('show') && menuBtn && !menuBtn.contains(event.target) && !nav.contains(event.target)) {
            nav.classList.remove('show');
            const btn = document.querySelector('.mobile-menu-btn i');
            if (btn) btn.className = 'fas fa-bars';
        }
    });
</script>

<!-- Add margin-top to body to account for fixed header -->
<style>
    body {
        padding-top: 85px;
    }
    
    @media (max-width: 768px) {
        body {
            padding-top: 70px;
        }
    }
</style>