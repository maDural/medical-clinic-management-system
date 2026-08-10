<?php
session_start();
include 'includes/db.php';

$is_logged_in = isset($_SESSION['user_id']);
$success_message = '';
$error_message = '';

// Get user info if logged in
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
    if ($user_query && $user_query->num_rows > 0) {
        $user = $user_query->fetch_assoc();
        $user_name = $user['fullname'];
        $user_email = $user['email'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_telehealth'])) {
    if ($is_logged_in) {
        $user_id = $_SESSION['user_id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);
        $concern = mysqli_real_escape_string($conn, $_POST['concern']);
        
        $query = "INSERT INTO telehealth_appointments (user_id, fullname, email, phone, appointment_date, appointment_time, reason, status, created_at) 
                  VALUES ('$user_id', '$name', '$email', '$phone', '$date', '$time', '$concern', 'pending', NOW())";
        
        if ($conn->query($query)) {
            $success_message = "✅ Your telehealth appointment has been scheduled! You will receive a video link via email before your appointment.";
        } else {
            $error_message = "❌ Something went wrong. Please try again.";
        }
    } else {
        $_SESSION['telehealth_data'] = $_POST;
        header("Location: login.php");
        exit();
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telehealth - Florida Medical Clinic</title>
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
        .telehealth-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .telehealth-hero::before {
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

        .telehealth-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .telehealth-hero p {
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
            max-width: 1200px;
            margin: 10px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* Benefits Section */
        .benefits-section {
            background: white;
            padding: 50px;
            border-radius: 24px;
            margin-bottom: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .benefits-section h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .benefits-section .section-subtitle {
            text-align: center;
            color: #667eea;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .benefit-card {
            text-align: center;
            padding: 30px 20px;
            background: #f8f9fa;
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .benefit-card:hover {
            transform: translateY(-8px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .benefit-card:hover h3,
        .benefit-card:hover p {
            color: white;
        }

        .benefit-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .benefit-card h3 {
            color: #1a1a2e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .benefit-card p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        /* Telehealth Grid */
        .telehealth-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        /* Appointment Form */
        .appointment-form {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .appointment-form h2 {
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .appointment-form h2 i {
            color: #667eea;
        }

        .form-subtitle {
            color: #888;
            margin-bottom: 25px;
            font-size: 14px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a1a2e;
            font-size: 13px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #667eea;
        }

        .required {
            color: #e74c3c;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Submit Button */
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Info Boxes */
        .info-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            transform: translateX(5px);
            background: white;
        }

        .info-box h3 {
            color: #1a1a2e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }

        .requirements-list li i {
            color: #28a745;
            margin-right: 12px;
            font-size: 14px;
        }

        /* Messages */
        .success-message, .error-message {
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

        .success-message {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .telehealth-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .telehealth-hero h1 {
                font-size: 32px;
            }

            .telehealth-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .benefits-section {
                padding: 30px;
            }

            .benefits-section h2 {
                font-size: 28px;
            }

            .appointment-form {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .appointment-form {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<div class="telehealth-hero">
    <h1>Telehealth Services</h1>
    <p>Quality healthcare from the comfort of your home</p>
</div>

<div class="container">
    <!-- Benefits Section -->
    <div class="benefits-section">
        <h2>Why Choose Telehealth?</h2>
        <div class="section-subtitle">Convenient, safe, and effective virtual care</div>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🏠</div>
                <h3>Stay Home</h3>
                <p>No travel or waiting rooms</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🕒</div>
                <h3>Save Time</h3>
                <p>Shorter wait times</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">💰</div>
                <h3>Cost Effective</h3>
                <p>Lower out-of-pocket costs</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🔒</div>
                <h3>Secure & Private</h3>
                <p>HIPAA compliant platform</p>
            </div>
        </div>
    </div>
    
    <div class="telehealth-grid">
        <!-- Appointment Form -->
        <div class="appointment-form">
            <h2>
                <i class="fas fa-calendar-plus"></i>
                Schedule Telehealth Visit
            </h2>
            <div class="form-subtitle">Book your virtual appointment today</div>
            
            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <input type="text" name="name" placeholder="Full name" value="<?php echo isset($user_name) ? htmlspecialchars($user_name) : ''; ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                        <input type="email" name="email" placeholder="Email address" value="<?php echo isset($user_email) ? htmlspecialchars($user_email) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone-alt"></i> Phone <span class="required">*</span></label>
                        <input type="tel" name="phone" placeholder="Phone number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-day"></i> Preferred Date <span class="required">*</span></label>
                        <input type="date" name="date" id="telehealthDate" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Preferred Time <span class="required">*</span></label>
                        <select name="time" required>
                            <option value="">Select time</option>
                            <option value="09:00">🕘 9:00 AM</option>
                            <option value="10:00">🕙 10:00 AM</option>
                            <option value="11:00">🕚 11:00 AM</option>
                            <option value="13:00">🕐 1:00 PM</option>
                            <option value="14:00">🕑 2:00 PM</option>
                            <option value="15:00">🕒 3:00 PM</option>
                            <option value="16:00">🕓 4:00 PM</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Reason for Visit <span class="required">*</span></label>
                    <textarea name="concern" rows="4" placeholder="Please describe your symptoms or reason for visit..." required></textarea>
                </div>
                
                <button type="submit" name="schedule_telehealth" class="submit-btn">
                    <i class="fas fa-video"></i> Schedule Virtual Visit
                </button>
            </form>
        </div>
        
        <!-- Info Boxes -->
        <div>
            <div class="info-box">
                <h3><i class="fas fa-notes-medical"></i> Conditions We Treat via Telehealth</h3>
                <ul class="requirements-list">
                    <li><i class="fas fa-check-circle"></i> Cold, Flu & Allergies</li>
                    <li><i class="fas fa-check-circle"></i> Sinus Infections</li>
                    <li><i class="fas fa-check-circle"></i> Urinary Tract Infections</li>
                    <li><i class="fas fa-check-circle"></i> Skin Rashes & Conditions</li>
                    <li><i class="fas fa-check-circle"></i> Follow-up Appointments</li>
                    <li><i class="fas fa-check-circle"></i> Medication Management</li>
                    <li><i class="fas fa-check-circle"></i> Mental Health Counseling</li>
                    <li><i class="fas fa-check-circle"></i> Prescription Refills</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h3><i class="fas fa-laptop"></i> Technical Requirements</h3>
                <ul class="requirements-list">
                    <li><i class="fas fa-mobile-alt"></i> Smartphone, tablet, or computer</li>
                    <li><i class="fas fa-camera"></i> Camera and microphone</li>
                    <li><i class="fas fa-wifi"></i> Stable internet connection</li>
                    <li><i class="fas fa-home"></i> Private, quiet space</li>
                    <li><i class="fas fa-envelope"></i> Valid email address</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h3><i class="fas fa-play-circle"></i> How It Works</h3>
                <ul class="requirements-list">
                    <li><i class="fas fa-calendar-check"></i> Schedule your appointment online</li>
                    <li><i class="fas fa-envelope"></i> Receive video link via email/SMS</li>
                    <li><i class="fas fa-link"></i> Click link at appointment time</li>
                    <li><i class="fas fa-user-md"></i> Consult with your doctor virtually</li>
                    <li><i class="fas fa-prescription-bottle"></i> Get prescriptions sent to your pharmacy</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('telehealthDate').setAttribute('min', today);
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>