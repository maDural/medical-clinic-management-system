<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $admin_reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
    
    $query = "UPDATE nurse_applications SET status = '$status', admin_reply = '$admin_reply' WHERE id = '$application_id'";
    
    if ($conn->query($query)) {
        $success = "Application status updated successfully!";
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $application_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Get file path to delete
    $get_file = $conn->query("SELECT diploma_file FROM nurse_applications WHERE id = '$application_id'");
    if ($get_file && $get_file->num_rows > 0) {
        $file = $get_file->fetch_assoc();
        if (!empty($file['diploma_file']) && file_exists('uploads/applications/' . $file['diploma_file'])) {
            unlink('uploads/applications/' . $file['diploma_file']);
        }
    }
    
    $delete_query = "DELETE FROM nurse_applications WHERE id = '$application_id'";
    
    if ($conn->query($delete_query)) {
        $success = "Application deleted successfully!";
        header("refresh:1;url=admin_applications.php");
    } else {
        $error = "Error deleting application: " . $conn->error;
    }
}

// Fetch all applications
$query = "SELECT * FROM nurse_applications ORDER BY 
          CASE 
            WHEN status = 'pending' THEN 1 
            WHEN status = 'reviewing' THEN 2 
            WHEN status = 'approved' THEN 3 
            WHEN status = 'rejected' THEN 4 
          END, 
          created_at DESC";
$result = $conn->query($query);

// Calculate statistics
$total = $conn->query("SELECT COUNT(*) as total FROM nurse_applications")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as pending FROM nurse_applications WHERE status = 'pending'")->fetch_assoc()['pending'];
$reviewing = $conn->query("SELECT COUNT(*) as reviewing FROM nurse_applications WHERE status = 'reviewing'")->fetch_assoc()['reviewing'];
$approved = $conn->query("SELECT COUNT(*) as approved FROM nurse_applications WHERE status = 'approved'")->fetch_assoc()['approved'];
$rejected = $conn->query("SELECT COUNT(*) as rejected FROM nurse_applications WHERE status = 'rejected'")->fetch_assoc()['rejected'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Applications - Admin Panel</title>
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
            background: linear-gradient(135deg, rgba(26, 41, 128, 0.85) 0%, rgba(38, 208, 206, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }

        .top-bar {
            background: rgba(255, 255, 255, 0.98);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.98);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a2e;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
            font-size: 13px;
        }

        .success, .error {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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

        .app-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .app-card:hover {
            background: rgba(255, 255, 255, 0.98);
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
            margin-bottom: 15px;
        }

        .app-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .app-info span {
            font-size: 14px;
            color: #555;
        }

        .app-info strong {
            color: #1a1a2e;
        }

        .app-id {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge {
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
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

        .app-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .app-detail {
            padding: 8px 0;
        }

        .app-detail label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            display: block;
            margin-bottom: 3px;
        }

        .app-detail p {
            color: #333;
            margin-top: 3px;
        }

        .file-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .file-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .reply-box {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 12px;
            margin-top: 10px;
            border-left: 4px solid #667eea;
        }

        .reply-box strong {
            color: #004085;
        }

        .form-inline {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eef2f6;
        }

        select, textarea {
            padding: 10px 14px;
            border: 2px solid #eef2f6;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            flex: 1;
            min-width: 200px;
            resize: vertical;
        }

        .update-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
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

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .top-bar h1 {
                font-size: 18px;
            }
            .app-body {
                grid-template-columns: 1fr;
            }
            .app-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-inline {
                flex-direction: column;
            }
            select, textarea, .update-btn, .delete-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>
            <i class="fas fa-users"></i>
            Nurse Applications
            <span style="font-size: 12px; background: rgba(102, 126, 234, 0.1); padding: 2px 10px; border-radius: 20px; color: #667eea;">
                <?php echo $total; ?> total
            </span>
        </h1>
        <div class="nav-links">
            <a href="admin_dashboard.php"><i class="fas fa-calendar-check"></i> Dashboard</a>
            <a href="admin_medical_requests.php"><i class="fas fa-folder-medical"></i> Medical Records</a>
            <a href="admin_feedback.php"><i class="fas fa-comment-dots"></i> Feedback</a>
            <a href="admin_contact_messages.php"><i class="fas fa-envelope"></i> Messages</a>
            <a href="admin_chat.php"><i class="fas fa-comments"></i> Live Chat</a>
            <a href="admin_applications.php" style="background: rgba(102, 126, 234, 0.1);"><i class="fas fa-users"></i> Applications</a>
            <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #856404;"><?php echo $pending; ?></div>
                <div class="stat-label">Pending Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #004085;"><?php echo $reviewing; ?></div>
                <div class="stat-label">Under Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #155724;"><?php echo $approved; ?></div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #721c24;"><?php echo $rejected; ?></div>
                <div class="stat-label">Rejected</div>
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
                <div class="app-card">
                    <div class="app-header">
                        <div class="app-info">
                            <span class="app-id">#<?php echo $row['id']; ?></span>
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['fullname']); ?></span>
                            <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                            <span><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($row['phone']); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                        </div>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </div>

                    <div class="app-body">
                        <div>
                            <div class="app-detail">
                                <label><i class="fas fa-graduation-cap"></i> Qualification</label>
                                <p><?php echo ucfirst(str_replace('_', ' ', $row['qualification'])); ?></p>
                            </div>
                            <div class="app-detail">
                                <label><i class="fas fa-clock"></i> Years of Experience</label>
                                <p><?php echo $row['experience']; ?> years</p>
                            </div>
                            <div class="app-detail">
                                <label><i class="fas fa-file-upload"></i> Diploma File</label>
                                <?php if ($row['diploma_file'] && file_exists('uploads/applications/' . $row['diploma_file'])): ?>
                                    <a href="uploads/applications/<?php echo $row['diploma_file']; ?>" class="file-download" target="_blank">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                <?php else: ?>
                                    <p style="color: #999;">No file uploaded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div class="app-detail">
                                <label><i class="fas fa-comment"></i> Message</label>
                                <p><?php echo htmlspecialchars($row['message'] ?? 'N/A'); ?></p>
                            </div>
                            <?php if ($row['admin_reply']): ?>
                                <div class="reply-box">
                                    <strong><i class="fas fa-reply"></i> Admin Reply:</strong>
                                    <p style="margin-top: 8px;"><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <form method="POST" class="form-inline">
                        <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                            <option value="reviewing" <?php echo $row['status'] == 'reviewing' ? 'selected' : ''; ?>>🔄 Reviewing</option>
                            <option value="approved" <?php echo $row['status'] == 'approved' ? 'selected' : ''; ?>>✅ Approved</option>
                            <option value="rejected" <?php echo $row['status'] == 'rejected' ? 'selected' : ''; ?>>❌ Rejected</option>
                        </select>
                        <textarea name="admin_reply" placeholder="Add reply to the applicant..."><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                        <button type="submit" name="update_status" class="update-btn">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this application?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>📭 No nurse applications submitted yet.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>