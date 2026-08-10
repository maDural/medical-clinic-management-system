<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$request_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

$query = "SELECT * FROM medical_record_requests WHERE id = '$request_id'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header("Location: admin_medical_requests.php");
    exit();
}

$request = $result->fetch_assoc();

// Get user info
$user_query = "SELECT fullname, email FROM users WHERE id = '{$request['user_id']}'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request - Medical Records</title>
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
        
        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .top-bar h1 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .top-bar h1 i {
            font-size: 28px;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .back-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(-3px);
        }
        
        /* Container */
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        /* Card */
        .card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeInUp 0.5s ease;
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
        
        .card-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .card-header h2 {
            color: #1a1a2e;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-header h2 i {
            color: #667eea;
            font-size: 22px;
        }
        
        .request-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
        }
        
        /* Detail Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .detail-row {
            background: #f8f9fa;
            padding: 15px 18px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .detail-row:hover {
            background: #f0f2ff;
            transform: translateX(5px);
        }
        
        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-label i {
            color: #667eea;
            font-size: 12px;
        }
        
        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
        }
        
        /* Full Width Row */
        .full-width-row {
            background: #f8f9fa;
            padding: 15px 18px;
            border-radius: 16px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .full-width-row:hover {
            background: #f0f2ff;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
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
        
        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .back-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .top-bar {
                padding: 12px 20px;
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
            
            .top-bar h1 {
                font-size: 18px;
            }
            
            .card {
                padding: 25px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h1>
        <i class="fas fa-file-medical"></i>
        Medical Record Request Details
    </h1>
    <a href="admin_medical_requests.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Requests
    </a>
</div>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-hashtag"></i>
                Request #<?php echo $request['id']; ?>
            </h2>
            <div class="request-badge">
                <i class="fas fa-calendar-alt"></i> 
                <?php echo date('M d, Y', strtotime($request['request_date'])); ?>
            </div>
        </div>
        
        <div class="details-grid">
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-user"></i> Patient Name
                </div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($user['fullname'] ?? 'N/A'); ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-envelope"></i> Patient Email
                </div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-tag"></i> Record Type
                </div>
                <div class="detail-value">
                    <?php 
                    $record_types = [
                        'full' => '📄 Complete Medical Records',
                        'summary' => '📋 Medical Summary',
                        'lab' => '🔬 Lab Results',
                        'imaging' => '📷 Imaging Reports',
                        'immunization' => '💉 Immunization Records',
                        'prescription' => '💊 Prescription History'
                    ];
                    echo $record_types[$request['record_type']] ?? ucfirst($request['record_type']);
                    ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-calendar-range"></i> Date Range
                </div>
                <div class="detail-value">
                    <?php echo $request['date_from'] ?? 'All records'; ?> to <?php echo $request['date_to'] ?? 'Present'; ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-truck"></i> Delivery Method
                </div>
                <div class="detail-value">
                    <?php echo ucfirst($request['delivery_method']); ?>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-bullseye"></i> Purpose
                </div>
                <div class="detail-value">
                    <?php echo ucfirst(str_replace('_', ' ', $request['purpose'])); ?>
                </div>
            </div>
        </div>
        
        <div class="full-width-row">
            <div class="detail-label">
                <i class="fas fa-flag-checkered"></i> Status
            </div>
            <div class="detail-value">
                <span class="status-badge status-<?php echo $request['status']; ?>">
                    <?php echo ucfirst($request['status']); ?>
                </span>
            </div>
        </div>
        
        <div class="full-width-row">
            <div class="detail-label">
                <i class="fas fa-clock"></i> Request Date & Time
            </div>
            <div class="detail-value">
                <?php echo date('F d, Y h:i A', strtotime($request['request_date'])); ?>
            </div>
        </div>
        
        <!-- Admin Reply Section (if exists) -->
        <?php if (!empty($request['admin_reply'])): ?>
        <div class="full-width-row" style="background: #e8f4f8;">
            <div class="detail-label">
                <i class="fas fa-reply"></i> Admin Reply
            </div>
            <div class="detail-value">
                <?php echo nl2br(htmlspecialchars($request['admin_reply'])); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Admin Message Section (if exists) -->
        <?php if (!empty($request['admin_message'])): ?>
        <div class="full-width-row" style="background: #d4edda;">
            <div class="detail-label">
                <i class="fas fa-comment-dots"></i> Message to Patient
            </div>
            <div class="detail-value">
                <?php echo nl2br(htmlspecialchars($request['admin_message'])); ?>
                <?php if (!empty($request['message_sent_at'])): ?>
                    <div style="font-size: 11px; color: #666; margin-top: 8px;">
                        <i class="fas fa-clock"></i> Sent: <?php echo date('M d, Y h:i A', strtotime($request['message_sent_at'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Attachment Section (if exists) -->
        <?php if (!empty($request['attachment']) && file_exists($request['attachment'])): ?>
        <div class="full-width-row" style="background: #e8f0fe;">
            <div class="detail-label">
                <i class="fas fa-paperclip"></i> Attachment
            </div>
            <div class="detail-value">
                <?php 
                $file_ext = strtolower(pathinfo($request['attachment'], PATHINFO_EXTENSION));
                $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                ?>
                
                <?php if (in_array($file_ext, $image_extensions)): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?php echo $request['attachment']; ?>" alt="Medical Document" style="max-width: 100%; max-height: 200px; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src)">
                    </div>
                <?php endif; ?>
                
                <a href="<?php echo $request['attachment']; ?>" download class="back-btn" style="background: #4285f4; margin-top: 0;">
                    <i class="fas fa-download"></i> Download <?php echo htmlspecialchars($request['attachment_name'] ?? 'Attachment'); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <a href="admin_medical_requests.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>
</div>

</body>
</html>