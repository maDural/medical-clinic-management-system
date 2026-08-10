<?php
session_start();
include 'includes/db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user name
$user_query = $conn->query("SELECT fullname FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];

// Fetch user's medical record requests
$query = "SELECT * FROM medical_record_requests WHERE user_id = '$user_id' ORDER BY request_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Florida Medical Clinic</title>
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
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-header p {
            color: rgba(255,255,255,0.8);
            margin-top: 8px;
            font-size: 14px;
        }

        /* Request Card */
        .request-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
            border-left: 4px solid #667eea;
            backdrop-filter: blur(5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .request-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        /* Request Header */
        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        .request-id {
            font-weight: 700;
            color: #667eea;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .request-id i {
            font-size: 18px;
        }

        .request-date {
            color: #888;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .request-date i {
            font-size: 12px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Request Details Grid */
        .request-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
        }

        .detail-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
        }

        .detail-value {
            color: #1a1a2e;
            font-size: 14px;
            font-weight: 500;
        }

        /* Message Boxes */
        .admin-message-box, .reply-box, .attachment-box {
            padding: 18px;
            border-radius: 16px;
            margin-top: 15px;
        }

        .admin-message-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .reply-box {
            background: #e8f4f8;
            border-left: 4px solid #667eea;
        }

        .attachment-box {
            background: #e8f0fe;
            border-left: 4px solid #4285f4;
        }

        .message-label {
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-text {
            color: #333;
            line-height: 1.7;
            font-size: 14px;
        }

        .message-date {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Download Link */
        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #4285f4;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .download-link:hover {
            background: #3367d6;
            transform: translateX(5px);
        }

        /* Status Info */
        .status-info {
            margin-top: 15px;
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-info.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-info.processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-info.completed {
            background: #d4edda;
            color: #155724;
        }

        .status-info.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-info i {
            font-size: 18px;
        }

        /* No Requests */
        .no-requests {
            text-align: center;
            padding: 60px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }

        .no-requests i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .no-requests p {
            color: #888;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .new-request-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .new-request-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Footer Link */
        .footer-link {
            text-align: center;
            margin-top: 20px;
        }

        .footer-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            padding: 10px 20px;
            border-radius: 30px;
        }

        .footer-link a:hover {
            gap: 12px;
            background: white;
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

        /* Attachment Preview Image */
        .attachment-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 12px;
            margin-bottom: 12px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
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

            .request-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .request-details {
                grid-template-columns: 1fr;
            }

            .page-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<!-- Decorative background element -->
<div class="bg-decoration">
    <i class="fas fa-clipboard-list"></i>
</div>

<div class="top-bar">
    <h2>
        <i class="fas fa-file-medical"></i>
        My Medical Record Requests
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
    <div class="page-header">
        <h2>
            <i class="fas fa-clipboard-list"></i>
            Your Requests
        </h2>
        <p>Track the status of your medical record requests and view responses</p>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
            $status = $row['status'];
            $status_class = '';
            $status_icon = '';
            $status_text = '';
            switch($status) {
                case 'processing':
                    $status_class = 'status-processing';
                    $status_icon = 'fa-spinner';
                    $status_text = 'Your request is being processed.';
                    break;
                case 'completed':
                    $status_class = 'status-completed';
                    $status_icon = 'fa-check-circle';
                    $status_text = 'Your request has been completed.';
                    break;
                case 'cancelled':
                    $status_class = 'status-cancelled';
                    $status_icon = 'fa-times-circle';
                    $status_text = 'This request has been cancelled.';
                    break;
                default:
                    $status_class = 'status-pending';
                    $status_icon = 'fa-clock';
                    $status_text = 'Your request is pending review.';
            }
            
            $record_types = [
                'full' => 'Complete Medical Records',
                'summary' => 'Medical Summary',
                'lab' => 'Lab Results',
                'imaging' => 'Imaging Reports',
                'immunization' => 'Immunization Records',
                'prescription' => 'Prescription History'
            ];
            
            $record_icons = [
                'full' => 'fa-folder-open',
                'summary' => 'fa-file-alt',
                'lab' => 'fa-flask',
                'imaging' => 'fa-camera',
                'immunization' => 'fa-syringe',
                'prescription' => 'fa-prescription-bottle'
            ];
            ?>
            <div class="request-card">
                <div class="request-header">
                    <div class="request-id">
                        <i class="fas fa-hashtag"></i>
                        Request #<?php echo $row['id']; ?>
                    </div>
                    <div class="request-date">
                        <i class="far fa-calendar-alt"></i>
                        Submitted: <?php echo date('F d, Y', strtotime($row['request_date'])); ?>
                    </div>
                    <span class="status-badge <?php echo $status_class; ?>">
                        <?php echo ucfirst($status); ?>
                    </span>
                </div>
                
                <div class="request-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas <?php echo $record_icons[$row['record_type']] ?? 'fa-file-medical'; ?>"></i>
                        </div>
                        <span class="detail-label">Record Type:</span>
                        <span class="detail-value"><?php echo $record_types[$row['record_type']] ?? ucfirst($row['record_type']); ?></span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-range"></i>
                        </div>
                        <span class="detail-label">Date Range:</span>
                        <span class="detail-value"><?php echo $row['date_from'] ?? 'All records'; ?> - <?php echo $row['date_to'] ?? 'Present'; ?></span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="detail-label">Delivery Method:</span>
                        <span class="detail-value"><?php echo ucfirst($row['delivery_method']); ?></span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <span class="detail-label">Purpose:</span>
                        <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $row['purpose'])); ?></span>
                    </div>
                </div>
                
                <!-- Admin Message Box -->
                <?php if (!empty($row['admin_message'])): ?>
                    <div class="admin-message-box">
                        <div class="message-label">
                            <i class="fas fa-comment-dots"></i> Message from Admin:
                        </div>
                        <div class="message-text">
                            <?php echo nl2br(htmlspecialchars($row['admin_message'])); ?>
                        </div>
                        <?php if (!empty($row['message_sent_at'])): ?>
                            <div class="message-date">
                                <i class="far fa-clock"></i>
                                Sent: <?php echo date('F d, Y h:i A', strtotime($row['message_sent_at'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Admin Reply Box -->
                <?php if (!empty($row['admin_reply'])): ?>
                    <div class="reply-box">
                        <div class="reply-label">
                            <i class="fas fa-reply"></i> Admin Reply:
                        </div>
                        <div class="message-text">
                            <?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Attachment Box -->
                <?php if (!empty($row['attachment']) && file_exists($row['attachment'])): ?>
                    <div class="attachment-box">
                        <div class="attachment-label">
                            <i class="fas fa-paperclip"></i> Doctor's Attachment:
                        </div>
                        <div class="attachment-preview">
                            <?php 
                            $file_ext = strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION));
                            $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                            ?>
                            
                            <?php if (in_array($file_ext, $image_extensions)): ?>
                                <img src="<?php echo $row['attachment']; ?>" alt="Medical Document" onclick="window.open(this.src)">
                            <?php endif; ?>
                            
                            <a href="<?php echo $row['attachment']; ?>" download class="download-link">
                                <i class="fas fa-download"></i>
                                <?php if (in_array($file_ext, $image_extensions)): ?>
                                    Download Image
                                <?php elseif ($file_ext == 'pdf'): ?>
                                    Download PDF
                                <?php elseif (in_array($file_ext, ['doc', 'docx'])): ?>
                                    Download Document
                                <?php else: ?>
                                    Download File
                                <?php endif; ?>
                            </a>
                        </div>
                        <div style="font-size: 11px; color: #666; margin-top: 10px;">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Uploaded by doctor on <?php echo !empty($row['message_sent_at']) ? date('F d, Y', strtotime($row['message_sent_at'])) : date('F d, Y', strtotime($row['request_date'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Status Info -->
                <div class="status-info <?php echo $status; ?>">
                    <i class="fas <?php echo $status_icon; ?>"></i>
                    <?php echo $status_text; ?>
                    <?php if ($status == 'pending'): ?>
                        We'll update you soon.
                    <?php elseif ($status == 'processing'): ?>
                        You will receive a response shortly.
                    <?php elseif ($status == 'completed'): ?>
                        Your records have been sent via your selected delivery method.
                    <?php elseif ($status == 'cancelled'): ?>
                        Please contact support if you have questions.
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-requests">
            <i class="fas fa-inbox"></i>
            <p>📭 You haven't made any medical record requests yet.</p>
            <a href="request_medical_records.php" class="new-request-btn">
                <i class="fas fa-plus-circle"></i> Request Medical Records
            </a>
        </div>
    <?php endif; ?>

    <div class="footer-link">
        <a href="request_medical_records.php">
            <i class="fas fa-arrow-left"></i> Make a New Request
        </a>
    </div>
</div>

</body>
</html>