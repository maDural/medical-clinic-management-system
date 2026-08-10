<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle status update and message
if (isset($_POST['update_status'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $admin_reply = isset($_POST['admin_reply']) ? mysqli_real_escape_string($conn, $_POST['admin_reply']) : '';
    $admin_message = isset($_POST['admin_message']) ? mysqli_real_escape_string($conn, $_POST['admin_message']) : '';
    
    // Handle file upload
    $attachment_path = '';
    $attachment_type = '';
    $attachment_name = '';
    
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/medical_records/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['attachment']['name']);
        $file_tmp = $_FILES['attachment']['tmp_name'];
        $file_size = $_FILES['attachment']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
        
        if (in_array($file_ext, $allowed_ext) && $file_size <= 5000000) {
            $attachment_path = $upload_dir . $file_name;
            $attachment_type = $file_ext;
            $attachment_name = $_FILES['attachment']['name'];
            
            if (!move_uploaded_file($file_tmp, $attachment_path)) {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type or file too large. Allowed: JPG, PNG, GIF, PDF, DOC, TXT (Max 5MB)";
        }
    }
    
    $update_query = "UPDATE medical_record_requests SET status = '$status'";
    
    if (!empty($admin_reply)) {
        $update_query .= ", admin_reply = '$admin_reply'";
    }
    
    if (!empty($admin_message)) {
        $update_query .= ", admin_message = '$admin_message', message_sent_at = NOW()";
    }
    
    if (!empty($attachment_path)) {
        $update_query .= ", attachment = '$attachment_path', attachment_type = '$attachment_type', attachment_name = '$attachment_name'";
    }
    
    $update_query .= " WHERE id = '$request_id'";
    
    if ($conn->query($update_query)) {
        $success = "Request updated successfully!";
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

// Handle delete request with file removal
if (isset($_GET['delete'])) {
    $request_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    $get_file = "SELECT attachment FROM medical_record_requests WHERE id = '$request_id'";
    $file_result = $conn->query($get_file);
    if ($file_result && $file_result->num_rows > 0) {
        $file_row = $file_result->fetch_assoc();
        if (!empty($file_row['attachment']) && file_exists($file_row['attachment'])) {
            unlink($file_row['attachment']);
        }
    }
    
    $delete_query = "DELETE FROM medical_record_requests WHERE id = '$request_id'";
    
    if ($conn->query($delete_query)) {
        $success = "Request deleted successfully!";
        header("refresh:1;url=admin_medical_requests.php");
    } else {
        $error = "Error deleting request: " . $conn->error;
    }
}

// Fetch all medical record requests
$query = "SELECT m.*, u.fullname as user_name, u.email as user_email 
          FROM medical_record_requests m 
          LEFT JOIN users u ON m.user_id = u.id 
          ORDER BY m.request_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Record Requests - Admin Panel</title>
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
            background: linear-gradient(135deg, rgba(26, 41, 128, 0.85) 0%, rgba(38, 208, 206, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1600&q=80') no-repeat center center fixed;
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

        .top-bar h1 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1a1a2e;
        }

        .top-bar h1 i {
            font-size: 28px;
            color: #667eea;
        }

        .nav-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #1a1a2e;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .logout-btn {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .logout-btn:hover {
            background: #e74c3c;
            color: white;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .stat-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Messages */
        .success, .error {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
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

        /* Request Card */
        .request-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .request-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            background: rgba(255, 255, 255, 0.98);
        }

        .request-header {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-bottom: 1px solid #eef2f6;
        }

        .request-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .request-id {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
        }

        .info-item i {
            color: #667eea;
            width: 16px;
        }

        .status-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .request-body {
            padding: 25px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-box {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 12px;
        }

        .detail-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .attachment-box, .reply-box, .admin-message-box {
            padding: 18px;
            border-radius: 16px;
            margin-top: 15px;
        }

        .attachment-box {
            background: #e8f0fe;
            border-left: 4px solid #4285f4;
        }

        .reply-box {
            background: #e8f4f8;
            border-left: 4px solid #667eea;
        }

        .admin-message-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .form-inline {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eef2f6;
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        select, textarea, input[type="file"] {
            padding: 12px 16px;
            border: 2px solid #eef2f6;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        select {
            background: white;
            cursor: pointer;
            min-width: 150px;
        }

        textarea {
            flex: 1;
            min-width: 250px;
            resize: vertical;
        }

        .message-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a1a2e;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-label i {
            color: #667eea;
        }

        .file-info {
            font-size: 11px;
            color: #999;
            margin-top: 6px;
        }

        .update-btn, .delete-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .update-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            text-decoration: none;
        }

        .delete-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #999;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .top-bar h1 {
                font-size: 18px;
            }

            .request-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-row {
                flex-direction: column;
                width: 100%;
            }

            select, textarea, .update-btn, .delete-btn {
                width: 100%;
            }

            .stats-container {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>
            <i class="fas fa-folder-medical"></i>
            Medical Record Requests
        </h1>
        <div class="nav-links">
            <a href="admin_dashboard.php"><i class="fas fa-calendar-check"></i> Appointments</a>
            <a href="admin_medical_requests.php" style="background: rgba(102, 126, 234, 0.1);"><i class="fas fa-folder-medical"></i> Medical Records</a>
            <a href="admin_feedback.php"><i class="fas fa-comment-dots"></i> Feedback</a>
            <a href="admin_contact_messages.php"><i class="fas fa-envelope"></i> Messages</a>
            <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <?php
        $total = $conn->query("SELECT COUNT(*) as total FROM medical_record_requests")->fetch_assoc()['total'];
        $pending = $conn->query("SELECT COUNT(*) as pending FROM medical_record_requests WHERE status = 'pending'")->fetch_assoc()['pending'];
        $processing = $conn->query("SELECT COUNT(*) as processing FROM medical_record_requests WHERE status = 'processing'")->fetch_assoc()['processing'];
        $completed = $conn->query("SELECT COUNT(*) as completed FROM medical_record_requests WHERE status = 'completed'")->fetch_assoc()['completed'];
        ?>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-number" style="color: #856404;"><?php echo $pending; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-number" style="color: #004085;"><?php echo $processing; ?></div>
                <div class="stat-label">Processing</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-number" style="color: #155724;"><?php echo $completed; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <span class="request-id">#<?php echo $row['id']; ?></span>
                            <span class="info-item"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['user_name'] ?? 'Guest'); ?></span>
                            <span class="info-item"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['user_email'] ?? 'N/A'); ?></span>
                            <span class="info-item"><i class="far fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($row['request_date'])); ?></span>
                        </div>
                        <span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
                    </div>
                    
                    <div class="request-body">
                        <div class="details-grid">
                            <div class="detail-box">
                                <div class="detail-label">Record Type</div>
                                <div class="detail-value"><?php 
                                    $types = ['full' => '📄 Complete Records', 'summary' => '📋 Medical Summary', 'lab' => '🔬 Lab Results', 'imaging' => '📷 Imaging Reports', 'immunization' => '💉 Immunization Records', 'prescription' => '💊 Prescription History'];
                                    echo $types[$row['record_type']] ?? ucfirst($row['record_type']);
                                ?></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-label">Date Range</div>
                                <div class="detail-value"><?php echo $row['date_from'] ?? 'All'; ?> to <?php echo $row['date_to'] ?? 'Present'; ?></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-label">Delivery Method</div>
                                <div class="detail-value"><?php echo ucfirst($row['delivery_method']); ?></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-label">Purpose</div>
                                <div class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $row['purpose'])); ?></div>
                            </div>
                        </div>
                        
                        <?php if (!empty($row['attachment'])): ?>
                        <div class="attachment-box">
                            <strong><i class="fas fa-paperclip"></i> Current Attachment:</strong>
                            <div><a href="<?php echo $row['attachment']; ?>" target="_blank" class="attachment-link">📄 <?php echo htmlspecialchars($row['attachment_name'] ?? 'Document'); ?></a></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($row['admin_reply'])): ?>
                        <div class="reply-box">
                            <strong><i class="fas fa-reply"></i> Admin Reply:</strong>
                            <p style="margin-top: 8px;"><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($row['admin_message'])): ?>
                        <div class="admin-message-box">
                            <strong><i class="fas fa-envelope"></i> Message to Patient:</strong>
                            <p style="margin-top: 8px;"><?php echo nl2br(htmlspecialchars($row['admin_message'])); ?></p>
                            <?php if (!empty($row['message_sent_at'])): ?>
                                <small style="color: #666;"><i class="far fa-clock"></i> Sent: <?php echo date('M d, Y h:i A', strtotime($row['message_sent_at'])); ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="form-inline" enctype="multipart/form-data">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            
                            <div class="form-row">
                                <select name="status">
                                    <option value="pending" <?php echo ($row['status'] ?? 'pending') == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                    <option value="processing" <?php echo ($row['status'] ?? '') == 'processing' ? 'selected' : ''; ?>>🔄 Processing</option>
                                    <option value="completed" <?php echo ($row['status'] ?? '') == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                                    <option value="cancelled" <?php echo ($row['status'] ?? '') == 'cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                </select>
                            </div>
                            
                            <div>
                                <div class="message-label"><i class="fas fa-reply-all"></i> Reply to Request (Optional)</div>
                                <textarea name="admin_reply" placeholder="Add a reply about the request..." rows="3"><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <div class="message-label"><i class="fas fa-comment"></i> Message to Patient (Optional)</div>
                                <textarea name="admin_message" placeholder="Send a personal message to the patient..." rows="3"><?php echo htmlspecialchars($row['admin_message'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <div class="message-label"><i class="fas fa-upload"></i> Upload Attachment (Optional)</div>
                                <input type="file" name="attachment" accept="image/*,.pdf,.doc,.docx,.txt">
                                <div class="file-info"><i class="fas fa-info-circle"></i> Allowed: Images, PDF, DOC, TXT (Max 5MB)</div>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" name="update_status" class="update-btn"><i class="fas fa-save"></i> Save All Changes</button>
                                <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this request? This will also delete any attached files.')"><i class="fas fa-trash-alt"></i> Delete Request</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>📭 No medical record requests found.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>