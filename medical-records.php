<?php
session_start();
include 'includes/db.php';

$is_logged_in = isset($_SESSION['user_id']);
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_records'])) {
    if ($is_logged_in) {
        $user_id = $_SESSION['user_id'];
        $record_type = mysqli_real_escape_string($conn, $_POST['record_type']);
        $date_from = !empty($_POST['date_from']) ? mysqli_real_escape_string($conn, $_POST['date_from']) : NULL;
        $date_to = !empty($_POST['date_to']) ? mysqli_real_escape_string($conn, $_POST['date_to']) : NULL;
        $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
        $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
        
        $query = "INSERT INTO medical_record_requests (user_id, record_type, date_from, date_to, delivery_method, purpose, status, request_date) 
                  VALUES ('$user_id', '$record_type', '$date_from', '$date_to', '$delivery_method', '$purpose', 'pending', NOW())";
        
        if ($conn->query($query)) {
            $success_message = "✅ Your request has been submitted successfully! You will receive your records within 7-14 business days.";
        } else {
            $error_message = "❌ Something went wrong. Please try again.";
        }
    } else {
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
    <title>Medical Records - Florida Medical Clinic</title>
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
        .records-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .records-hero::before {
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

        .records-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .records-hero p {
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
            margin: 40px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* Records Grid */
        .records-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        /* Request Form */
        .request-form {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .request-form h2 {
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .request-form h2 i {
            color: #667eea;
        }

        .form-subtitle {
            color: #666;
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

        .form-group input, .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Info Cards */
        .info-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .info-card h3 {
            color: #1a1a2e;
            margin-bottom: 18px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h3 i {
            color: #667eea;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .info-list li:hover {
            transform: translateX(5px);
            color: #667eea;
        }

        .info-list li i {
            margin-right: 12px;
            color: #28a745;
            width: 20px;
        }

        .portal-link {
            display: inline-block;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .portal-link:hover {
            transform: translateX(5px);
            color: #764ba2;
        }

        .contact-details {
            margin-top: 15px;
        }

        .contact-details p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }

        .contact-details i {
            width: 25px;
            color: #667eea;
        }

        /* Messages */
        .success-message, .error-message {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
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
            .records-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .records-hero h1 {
                font-size: 32px;
            }

            .records-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .request-form {
                padding: 30px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .request-form {
                padding: 25px;
            }

            .request-form h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="records-hero">
    <h1>Medical Records</h1>
    <p>Request and access your health information securely</p>
</div>

<div class="container">
    <div class="records-grid">
        <!-- Request Form -->
        <div class="request-form">
            <h2>
                <i class="fas fa-file-medical"></i>
                Request Medical Records
            </h2>
            <div class="form-subtitle">Fill out the form below to request your records</div>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Type of Records <span class="required">*</span></label>
                    <select name="record_type" required>
                        <option value="full">📄 Complete Medical Records</option>
                        <option value="summary">📋 Medical Summary</option>
                        <option value="lab">🔬 Lab Results</option>
                        <option value="imaging">📷 Imaging Reports (X-ray, MRI, CT Scan)</option>
                        <option value="immunization">💉 Immunization Records</option>
                        <option value="prescription">💊 Prescription History</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date From</label>
                        <input type="date" name="date_from" id="date_from">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date To</label>
                        <input type="date" name="date_to" id="date_to">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-truck"></i> Delivery Method <span class="required">*</span></label>
                    <select name="delivery_method" required>
                        <option value="portal">🖥️ Patient Portal (Free - Instant Access)</option>
                        <option value="email">📧 Email (Free)</option>
                        <option value="pickup">🏥 Pickup In Person (Free)</option>
                        <option value="mail">📬 Mail ($15 Fee)</option>
                        <option value="fax">📠 Fax ($10 Fee)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-bullseye"></i> Purpose of Request <span class="required">*</span></label>
                    <select name="purpose" required>
                        <option value="personal">👤 Personal Use</option>
                        <option value="new_doctor">👨‍⚕️ New Healthcare Provider</option>
                        <option value="insurance">🏦 Insurance Purposes</option>
                        <option value="legal">⚖️ Legal Purposes</option>
                        <option value="employment">💼 Employment Requirements</option>
                    </select>
                </div>
                
                <button type="submit" name="request_records" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </form>
        </div>
        
        <!-- Information Cards -->
        <div>
            <div class="info-card">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    Information About Medical Records
                </h3>
                <ul class="info-list">
                    <li><i class="fas fa-clock"></i> Processing time: 7-14 business days</li>
                    <li><i class="fas fa-lock"></i> Records are confidential and protected by HIPAA</li>
                    <li><i class="fas fa-gavel"></i> You have the right to access your records</li>
                    <li><i class="fas fa-dollar-sign"></i> Fees may apply for copies and mailing</li>
                    <li><i class="fas fa-archive"></i> Records available for up to 7 years</li>
                </ul>
            </div>
            
            <div class="info-card">
                <h3>
                    <i class="fas fa-key"></i>
                    Patient Portal Access
                </h3>
                <p>Access your records instantly through our secure patient portal. View test results, request prescriptions, and message your doctor.</p>
                <a href="login.php" class="portal-link">
                    Login to Portal <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="info-card">
                <h3>
                    <i class="fas fa-headset"></i>
                    Need Help?
                </h3>
                <p>Contact our Medical Records Department:</p>
                <div class="contact-details">
                    <p><i class="fas fa-phone-alt"></i> Phone: (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> Email: records@floridamedical.com</p>
                    <p><i class="fas fa-clock"></i> Hours: Mon-Fri, 9am - 5pm</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set max date to today
    const today = new Date().toISOString().split('T')[0];
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    if (dateFrom) dateFrom.setAttribute('max', today);
    if (dateTo) dateTo.setAttribute('max', today);
    
    // Date validation
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            dateTo.setAttribute('min', this.value);
            if (dateTo.value && dateTo.value < this.value) {
                dateTo.value = '';
                alert('Date To cannot be earlier than Date From');
            }
        });
        
        dateTo.addEventListener('change', function() {
            if (dateFrom.value && this.value < dateFrom.value) {
                this.value = '';
                alert('Date To cannot be earlier than Date From');
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>