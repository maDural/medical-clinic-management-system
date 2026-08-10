<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';
$application = null;
$found = false;

if (isset($_POST['check_status'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if application exists
    $query = "SELECT * FROM nurse_applications WHERE email = '$email' ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $application = $result->fetch_assoc();
        $found = true;
    } else {
        $error = "No application found with this email address. Please make sure you used the same email you applied with.";
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status - Florida Medical Clinic</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
        }

        .status-hero {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .status-hero h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .status-hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .container {
            max-width: 800px;
            margin: -30px auto 50px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .status-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .status-card h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .status-card .subtitle {
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #f093fb;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f093fb;
            background: white;
            box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.1);
        }

        .check-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        }

        .check-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #dc3545;
        }

        .app-result {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #eef2f6;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .result-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .status-badge-large {
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-reviewing {
            background: #cce5ff;
            color: #004085;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .result-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .result-item {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 12px;
        }

        .result-item label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .result-item p {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            margin-top: 4px;
        }

        .admin-reply-box {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            border-left: 4px solid #667eea;
        }

        .admin-reply-box strong {
            color: #004085;
        }

        .admin-reply-box p {
            margin-top: 8px;
            color: #333;
            line-height: 1.6;
        }

        .status-message {
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            font-weight: 500;
        }

        .status-message.pending {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #856404;
        }

        .status-message.reviewing {
            background: #cce5ff;
            color: #004085;
            border-left: 4px solid #004085;
        }

        .status-message.approved {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .status-message.rejected {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #f093fb;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            gap: 12px;
        }

        @media (max-width: 768px) {
            .status-hero h1 {
                font-size: 32px;
            }
            .status-card {
                padding: 25px;
            }
            .result-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="status-hero">
    <div class="container" style="max-width: 800px;">
        <h1>Check Application Status</h1>
        <p>Enter the email address you used to apply to check your application status</p>
    </div>
</div>

<div class="container">
    <div class="status-card">
        <h2>Application Status</h2>
        <div class="subtitle">Enter your email to track your application progress</div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!$found): ?>
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" placeholder="Enter the email you used to apply" required>
            </div>
            <button type="submit" name="check_status" class="check-btn">
                <i class="fas fa-search"></i> Check Status
            </button>
        </form>
        <?php endif; ?>

        <?php if ($found && $application): ?>
        <div class="app-result">
            <div class="result-header">
                <h3>Application #<?php echo $application['id']; ?></h3>
                <span class="status-badge-large status-<?php echo $application['status']; ?>">
                    <?php echo ucfirst($application['status']); ?>
                </span>
            </div>

            <div class="result-details">
                <div class="result-item">
                    <label>Full Name</label>
                    <p><?php echo htmlspecialchars($application['fullname']); ?></p>
                </div>
                <div class="result-item">
                    <label>Email</label>
                    <p><?php echo htmlspecialchars($application['email']); ?></p>
                </div>
                <div class="result-item">
                    <label>Qualification</label>
                    <p><?php echo ucfirst(str_replace('_', ' ', $application['qualification'])); ?></p>
                </div>
                <div class="result-item">
                    <label>Submitted On</label>
                    <p><?php echo date('F d, Y', strtotime($application['created_at'])); ?></p>
                </div>
            </div>

            <div class="status-message <?php echo $application['status']; ?>">
                <?php if ($application['status'] == 'pending'): ?>
                    <i class="fas fa-clock"></i> Your application is pending review. We'll update you within 5-7 business days.
                <?php elseif ($application['status'] == 'reviewing'): ?>
                    <i class="fas fa-spinner fa-pulse"></i> Your application is currently being reviewed. You will receive feedback soon.
                <?php elseif ($application['status'] == 'approved'): ?>
                    <i class="fas fa-check-circle"></i> Congratulations! Your application has been approved. Our team will contact you shortly with next steps.
                <?php elseif ($application['status'] == 'rejected'): ?>
                    <i class="fas fa-times-circle"></i> We regret to inform you that your application was not successful at this time. Please check the admin reply below for more details.
                <?php endif; ?>
            </div>

            <?php if ($application['admin_reply']): ?>
                <div class="admin-reply-box">
                    <strong><i class="fas fa-reply"></i> Message from Admin:</strong>
                    <p><?php echo nl2br(htmlspecialchars($application['admin_reply'])); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($application['status'] == 'approved'): ?>
                <div style="background: #d4edda; padding: 15px; border-radius: 12px; margin-top: 15px;">
                    <strong><i class="fas fa-info-circle"></i> What happens next?</strong>
                    <p style="margin-top: 8px; color: #155724;">
                        Our recruitment team will contact you within 2-3 business days to discuss the next steps 
                        in the onboarding process. Please ensure your contact details are up to date.
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($application['status'] == 'rejected'): ?>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin-top: 15px;">
                    <p style="color: #666; font-size: 14px;">
                        <i class="fas fa-lightbulb" style="color: #f093fb;"></i>
                        We encourage you to continue developing your skills and consider reapplying in the future. 
                        Check our website regularly for new opportunities.
                    </p>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; text-align: center; font-size: 13px; color: #888;">
                <i class="fas fa-calendar-alt"></i> 
                Last updated: <?php echo date('F d, Y h:i A', strtotime($application['updated_at'] ?? $application['created_at'])); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="back-link">
            <a href="nurses.php">
                <i class="fas fa-arrow-left"></i> Back to Nurses Page
            </a>
        </div>

        <?php if ($found): ?>
        <div style="text-align: center; margin-top: 15px;">
            <a href="application_status.php" style="color: #f093fb; font-size: 14px;">
                <i class="fas fa-search"></i> Check another application
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>