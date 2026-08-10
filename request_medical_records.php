<?php
session_start();
include 'includes/db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];
$user_email = $user['email'];

$success = '';
$error = '';

if (isset($_POST['submit_request'])) {
    $record_type = mysqli_real_escape_string($conn, $_POST['record_type']);
    $date_from = !empty($_POST['date_from']) ? mysqli_real_escape_string($conn, $_POST['date_from']) : NULL;
    $date_to = !empty($_POST['date_to']) ? mysqli_real_escape_string($conn, $_POST['date_to']) : NULL;
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    
    $query = "INSERT INTO medical_record_requests (user_id, record_type, date_from, date_to, delivery_method, purpose, status, request_date) 
              VALUES ('$user_id', '$record_type', '$date_from', '$date_to', '$delivery_method', '$purpose', 'pending', NOW())";
    
    if ($conn->query($query)) {
        $success = "✅ Your medical record request has been submitted successfully! You will be notified once it's processed.";
    } else {
        $error = "❌ Error submitting request: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Medical Records - Florida Medical Clinic</title>
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
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        /* Top Bar */
        .top-bar {
            background: rgba(255, 255, 255, 0.98);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar h2 {
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-bar h2 i {
            color: #667eea;
            font-size: 24px;
        }

        .top-bar-buttons {
            display: flex;
            gap: 12px;
        }

        .back-btn, .logout-btn {
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn {
            background: #667eea;
            color: white;
        }

        .back-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Container */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }

        /* Form Box */
        .form-box {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 580px;
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
            backdrop-filter: blur(5px);
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

        .form-inner {
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .welcome {
            text-align: center;
            color: #667eea;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        /* Messages */
        .success, .error {
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

        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 13px;
        }

        label i {
            margin-right: 8px;
            color: #667eea;
        }

        .required {
            color: #e74c3c;
        }

        select, input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        select:focus, input:focus {
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

        /* Info Box */
        .info-box {
            background: #e8f4f8;
            padding: 16px 18px;
            border-radius: 16px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }

        .info-box strong {
            color: #004085;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .info-box ul {
            margin-left: 20px;
            color: #555;
            font-size: 13px;
            line-height: 1.7;
        }

        .info-box li {
            margin-bottom: 6px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
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
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Info Note */
        .info-note {
            background: #f8f9fa;
            padding: 14px 18px;
            border-radius: 14px;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .info-note i {
            color: #667eea;
            font-size: 14px;
        }

        /* Decorative Element */
        .bg-decoration {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 100px;
            pointer-events: none;
            z-index: 0;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-inner {
                padding: 30px 25px;
            }

            h2 {
                font-size: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .top-bar {
                padding: 12px 20px;
            }

            .top-bar h2 {
                font-size: 18px;
            }

            .top-bar h2 i {
                font-size: 20px;
            }

            .back-btn, .logout-btn {
                padding: 6px 14px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<!-- Decorative background element -->
<div class="bg-decoration">
    <i class="fas fa-file-medical"></i>
</div>

<div class="top-bar">
    <h2>
        <i class="fas fa-file-medical"></i>
        Request Medical Records
    </h2>
    <div class="top-bar-buttons">
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="container">
    <div class="form-box">
        <div class="form-inner">
            <h2>Medical Records Request</h2>
            <div class="subtitle">Request access to your health information</div>
            <div class="welcome">
                <i class="fas fa-hand-wave"></i> Welcome, <?php echo htmlspecialchars($user_name); ?>!
            </div>

            <?php if ($success): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Type of Records <span class="required">*</span></label>
                    <select name="record_type" required>
                        <option value="">Select record type</option>
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
                        <label><i class="fas fa-calendar-alt"></i> Date From (Optional)</label>
                        <input type="date" name="date_from" id="date_from">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date To (Optional)</label>
                        <input type="date" name="date_to" id="date_to">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-truck"></i> Delivery Method <span class="required">*</span></label>
                    <select name="delivery_method" required>
                        <option value="">Select delivery method</option>
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
                        <option value="">Select purpose</option>
                        <option value="personal">👤 Personal Use</option>
                        <option value="new_doctor">👨‍⚕️ New Healthcare Provider</option>
                        <option value="insurance">🏦 Insurance Purposes</option>
                        <option value="legal">⚖️ Legal Purposes</option>
                        <option value="employment">💼 Employment Requirements</option>
                    </select>
                </div>

                <div class="info-box">
                    <strong><i class="fas fa-info-circle"></i> Important Information:</strong>
                    <ul>
                        <li>Processing time: 7-14 business days</li>
                        <li>Records are confidential and protected by HIPAA</li>
                        <li>Fees may apply for copies and mailing</li>
                        <li>You will receive updates on your request status</li>
                    </ul>
                </div>

                <button type="submit" name="submit_request" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>

                <div class="info-note">
                    <i class="fas fa-headset"></i>
                    Need help? Call our Medical Records Department at (555) 123-4567
                </div>
            </form>
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
            if (dateTo.value && this.value > dateTo.value) {
                dateTo.value = '';
                alert('Date To cannot be earlier than Date From');
            }
            dateTo.setAttribute('min', this.value);
        });
        
        dateTo.addEventListener('change', function() {
            if (dateFrom.value && this.value < dateFrom.value) {
                this.value = '';
                alert('Date To cannot be earlier than Date From');
            }
        });
    }
</script>

</body>
</html>