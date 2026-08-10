<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap - Florida Medical Clinic</title>
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
            background: #f0f2f5;
        }

        /* Hero Section */
        .sitemap-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .sitemap-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .sitemap-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .sitemap-hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 10px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* Sitemap Grid */
        .sitemap-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        /* Sitemap Column */
        .sitemap-column {
            background: white;
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .sitemap-column:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .sitemap-column h2 {
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .sitemap-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .sitemap-list li {
            margin-bottom: 12px;
        }

        .sitemap-list a {
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 12px;
        }

        .sitemap-list a:hover {
            color: #667eea;
            background: #f8f9ff;
            transform: translateX(5px);
        }

        .sitemap-list .icon {
            width: 32px;
            height: 32px;
            background: #f0f2f5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .sitemap-list a:hover .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .sitemap-list .sub-links {
            margin-left: 44px;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .sitemap-list .sub-links li {
            margin-bottom: 8px;
        }

        .sitemap-list .sub-links a {
            padding: 6px 12px;
            font-size: 13px;
        }

        .sitemap-list .sub-links .icon {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }

        /* Footer */
        .sitemap-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 25px;
            border-top: 1px solid #eef2f6;
        }

        .sitemap-footer p {
            color: #888;
            font-size: 14px;
        }

        .sitemap-footer a {
            color: #667eea;
            text-decoration: none;
        }

        .sitemap-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sitemap-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .sitemap-hero h1 {
                font-size: 32px;
            }

            .sitemap-hero p {
                font-size: 16px;
            }

            .sitemap-grid {
                gap: 20px;
            }

            .sitemap-column {
                padding: 25px;
            }

            .sitemap-column h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .sitemap-column {
                padding: 20px;
            }

            .sitemap-list a {
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>

<div class="sitemap-hero">
    <h1>Site Map</h1>
    <p>Find your way around our website - all pages organized for easy navigation</p>
</div>

<div class="container">
    <div class="sitemap-grid">
        <!-- Column 1: Main Pages -->
        <div class="sitemap-column">
            <h2><i class="fas fa-home"></i> Main Pages</h2>
            <ul class="sitemap-list">
                <li><a href="index.php"><span class="icon"><i class="fas fa-home"></i></span> Home</a></li>
                <li><a href="about.php"><span class="icon"><i class="fas fa-stethoscope"></i></span> About Us</a></li>
                <li><a href="services.php"><span class="icon"><i class="fas fa-hospital-user"></i></span> Our Services</a></li>
                <li><a href="contact.php"><span class="icon"><i class="fas fa-envelope"></i></span> Contact Us</a></li>
                <li><a href="faq.php"><span class="icon"><i class="fas fa-question-circle"></i></span> FAQ</a></li>
                <li><a href="sitemap.php"><span class="icon"><i class="fas fa-map"></i></span> Site Map</a></li>
            </ul>
        </div>
        
        <!-- Column 2: Patient Portal -->
        <div class="sitemap-column">
            <h2><i class="fas fa-user-lock"></i> Patient Portal</h2>
            <ul class="sitemap-list">
                <li><a href="register.php"><span class="icon"><i class="fas fa-user-plus"></i></span> Create Account</a></li>
                <li><a href="login.php"><span class="icon"><i class="fas fa-sign-in-alt"></i></span> Login</a></li>
                <li><a href="dashboard.php"><span class="icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard</a></li>
                <li><a href="appointment.php"><span class="icon"><i class="fas fa-calendar-check"></i></span> Book Appointment</a></li>
                <li><a href="my_appointments.php"><span class="icon"><i class="fas fa-calendar-alt"></i></span> My Appointments</a></li>
                <li><a href="forgot.php"><span class="icon"><i class="fas fa-key"></i></span> Forgot Password</a></li>
                <li><a href="reset_password.php"><span class="icon"><i class="fas fa-sync-alt"></i></span> Reset Password</a></li>
            </ul>
        </div>
        
        <!-- Column 3: Medical Services -->
        <div class="sitemap-column">
            <h2><i class="fas fa-stethoscope"></i> Medical Services</h2>
            <ul class="sitemap-list">
                <li><a href="services.php#primary"><span class="icon"><i class="fas fa-clinic-medical"></i></span> Primary Care</a></li>
                <li><a href="services.php#cardiology"><span class="icon"><i class="fas fa-heartbeat"></i></span> Cardiology</a></li>
                <li><a href="services.php#neurology"><span class="icon"><i class="fas fa-brain"></i></span> Neurology</a></li>
                <li><a href="services.php#pediatrics"><span class="icon"><i class="fas fa-child"></i></span> Pediatrics</a></li>
                <li><a href="services.php#womens"><span class="icon"><i class="fas fa-female"></i></span> Women's Health</a></li>
                <li><a href="services.php#dental"><span class="icon"><i class="fas fa-tooth"></i></span> Dental Care</a></li>
                <li><a href="telehealth.php"><span class="icon"><i class="fas fa-video"></i></span> Telehealth</a></li>
            </ul>
        </div>
        
        <!-- Column 4: Patient Resources -->
        <div class="sitemap-column">
            <h2><i class="fas fa-folder-open"></i> Patient Resources</h2>
            <ul class="sitemap-list">
                <li><a href="insurance.php"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Insurance Info</a></li>
                <li><a href="medical_records.php"><span class="icon"><i class="fas fa-file-medical"></i></span> Medical Records</a></li>
                <li><a href="request_medical_records.php"><span class="icon"><i class="fas fa-file-export"></i></span> Request Records</a></li>
                <li><a href="my_requests.php"><span class="icon"><i class="fas fa-clipboard-list"></i></span> My Requests</a></li>
                <li><a href="my_messages.php"><span class="icon"><i class="fas fa-comment-dots"></i></span> My Messages</a></li>
                <li><a href="submit_feedback.php"><span class="icon"><i class="fas fa-comment"></i></span> Submit Feedback</a></li>
            </ul>
        </div>
        
        <!-- Column 5: Legal & Policies -->
        <div class="sitemap-column">
            <h2><i class="fas fa-gavel"></i> Legal & Policies</h2>
            <ul class="sitemap-list">
                <li><a href="privacy.php"><span class="icon"><i class="fas fa-shield-alt"></i></span> Privacy Policy</a></li>
                <li><a href="terms.php"><span class="icon"><i class="fas fa-file-contract"></i></span> Terms of Service</a></li>
                <li><a href="accessibility.php"><span class="icon"><i class="fas fa-universal-access"></i></span> Accessibility</a></li>
                <li><a href="privacy.php#hipaa"><span class="icon"><i class="fas fa-hospital"></i></span> HIPAA Compliance</a></li>
            </ul>
        </div>
        
        <!-- Column 6: Contact Information -->
        <div class="sitemap-column">
            <h2><i class="fas fa-address-card"></i> Contact Info</h2>
            <ul class="sitemap-list">
                <li><a href="contact.php"><span class="icon"><i class="fas fa-envelope"></i></span> Contact Form</a></li>
                <li><a href="tel:5551234567"><span class="icon"><i class="fas fa-phone-alt"></i></span> Call Us: (555) 123-4567</a></li>
                <li><a href="mailto:info@floridamedical.com"><span class="icon"><i class="fas fa-envelope"></i></span> Email Us</a></li>
                <li><a href="contact.php#map"><span class="icon"><i class="fas fa-map-marker-alt"></i></span> Find Our Location</a></li>
                <li><a href="contact.php#hours"><span class="icon"><i class="fas fa-clock"></i></span> Business Hours</a></li>
            </ul>
        </div>
    </div>
    
    <div class="sitemap-footer">
        <p>Can't find what you're looking for? <a href="contact.php">Contact our support team</a> for assistance.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>