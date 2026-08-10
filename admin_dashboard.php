<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle appointment status update
if (isset($_POST['update_status'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['appointment_type']);
    
    if ($appointment_type == 'in_person') {
        $query = "UPDATE appointments SET status = '$status' WHERE id = '$appointment_id'";
    } else {
        $query = "UPDATE telehealth_appointments SET status = '$status' WHERE id = '$appointment_id'";
    }
    
    if ($conn->query($query)) {
        $success = "Appointment status updated successfully!";
        header("refresh:1;url=admin_dashboard.php");
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

// Get active chat count for badge
$active_chats_query = "SELECT COUNT(*) as count FROM chat_sessions WHERE status = 'active'";
$active_chats_result = $conn->query($active_chats_query);
$active_chats = $active_chats_result->fetch_assoc()['count'];

// Get unread message count
$unread_messages_query = "SELECT COUNT(*) as count FROM chat_messages cm JOIN chat_sessions cs ON cm.session_id = cs.id WHERE cs.status = 'active' AND cm.sender_type = 'user' AND cm.is_read = 0";
$unread_messages_result = $conn->query($unread_messages_query);
$unread_messages = $unread_messages_result->fetch_assoc()['count'];

// Check if nurse_applications table exists
$table_check = $conn->query("SHOW TABLES LIKE 'nurse_applications'");
$pending_applications = 0;
if ($table_check->num_rows > 0) {
    $pending_apps = $conn->query("SELECT COUNT(*) as count FROM nurse_applications WHERE status = 'pending'");
    if ($pending_apps) {
        $pending_applications = $pending_apps->fetch_assoc()['count'];
    }
}

// Fetch all appointments
$in_person_query = "SELECT * FROM appointments ORDER BY appointment_date DESC, appointment_time ASC";
$in_person_result = $conn->query($in_person_query);

$telehealth_query = "SELECT * FROM telehealth_appointments ORDER BY appointment_date DESC, appointment_time ASC";
$telehealth_result = $conn->query($telehealth_query);

// Check if we have a success message from impersonation
if (isset($_SESSION['admin_message'])) {
    $success = $_SESSION['admin_message'];
    unset($_SESSION['admin_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Florida Medical Clinic</title>
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
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px 20px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.98);
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }
        
        .stat-number {
            font-size: 36px;
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
        
        .impersonation-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin: 0 30px 30px 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 2px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .impersonation-panel:hover {
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .impersonation-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f6;
        }
        
        .impersonation-header h3 {
            color: #1a1a2e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .impersonation-header h3 i {
            color: #ff9800;
            margin-right: 8px;
        }
        
        .impersonation-header p {
            color: #666;
            font-size: 13px;
        }
        
        .stop-impersonate-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .stop-impersonate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .impersonate-self-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .impersonate-self-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .appointments-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px 30px;
            position: relative;
            z-index: 1;
        }
        
        .section-title {
            font-size: 22px;
            color: white;
            margin: 25px 0 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid rgba(255,255,255,0.3);
            display: inline-block;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .appointment-table {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        td {
            padding: 14px 15px;
            border-bottom: 1px solid #eef2f6;
            font-size: 13px;
            color: #1a1a2e;
        }
        
        tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        select {
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid #eef2f6;
            font-size: 12px;
            background: white;
            cursor: pointer;
        }
        
        .update-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .update-btn:hover {
            background: #764ba2;
            transform: translateY(-1px);
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #999;
        }
        
        .doctor-name {
            font-weight: 500;
            color: #1a1a2e;
        }
        
        .quick-links {
            margin-top: 20px;
            text-align: center;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .quick-link-btn {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #667eea;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .quick-link-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .chat-badge-link {
            background: rgba(255, 193, 7, 0.95);
            color: #333;
        }
        
        .chat-badge-link:hover {
            background: #ffc107;
            color: #333;
        }
        
        .active-chats-badge {
            background: #e74c3c;
            color: white;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .notification-badge {
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 10px;
            margin-left: 5px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .applications-link {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white !important;
        }
        
        .applications-link:hover {
            background: linear-gradient(135deg, #e07aeb 0%, #e74c6c 100%) !important;
            color: white !important;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                padding: 20px;
                gap: 15px;
            }
            .appointments-section {
                padding: 0 20px 20px;
            }
            .impersonation-panel {
                margin: 0 15px 20px 15px;
                padding: 20px;
            }
            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .top-bar h1 {
                font-size: 18px;
            }
            .nav-links {
                justify-content: center;
            }
            .nav-links a {
                padding: 6px 12px;
                font-size: 11px;
            }
        }
        
        @media (max-width: 480px) {
            .stat-number {
                font-size: 28px;
            }
            .quick-links {
                flex-direction: column;
                align-items: center;
            }
            .quick-link-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>
            <i class="fas fa-shield-alt"></i>
            Admin Dashboard
            <span style="font-size: 12px; background: rgba(102, 126, 234, 0.1); padding: 2px 10px; border-radius: 20px; color: #667eea;">Florida Medical Clinic</span>
        </h1>
        <div class="nav-links">
            <a href="admin_dashboard.php" style="background: rgba(102, 126, 234, 0.1);">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
            <a href="admin_medical_requests.php">
                <i class="fas fa-folder-medical"></i> Medical Records
            </a>
            <a href="admin_feedback.php">
                <i class="fas fa-comment-dots"></i> Feedback
            </a>
            <a href="admin_contact_messages.php">
                <i class="fas fa-envelope"></i> Messages
            </a>
            <a href="admin_applications.php" class="applications-link">
                <i class="fas fa-users"></i> Nurse Applications
                <?php if ($pending_applications > 0): ?>
                    <span class="notification-badge"><?php echo $pending_applications; ?></span>
                <?php endif; ?>
            </a>
            <a href="admin_logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <?php
    $total_in_person = $in_person_result ? $in_person_result->num_rows : 0;
    $total_telehealth = $telehealth_result ? $telehealth_result->num_rows : 0;
    
    $pending_count = 0;
    $confirmed_count = 0;
    $completed_count = 0;
    $cancelled_count = 0;
    
    if ($in_person_result && $in_person_result->num_rows > 0) {
        $in_person_result->data_seek(0);
        while ($row = $in_person_result->fetch_assoc()) {
            $status = $row['status'] ?? 'pending';
            if ($status == 'pending') $pending_count++;
            if ($status == 'confirmed') $confirmed_count++;
            if ($status == 'completed') $completed_count++;
            if ($status == 'cancelled') $cancelled_count++;
        }
        $in_person_result->data_seek(0);
    }
    
    if ($telehealth_result && $telehealth_result->num_rows > 0) {
        $telehealth_result->data_seek(0);
        while ($row = $telehealth_result->fetch_assoc()) {
            $status = $row['status'] ?? 'pending';
            if ($status == 'pending') $pending_count++;
            if ($status == 'confirmed') $confirmed_count++;
            if ($status == 'completed') $completed_count++;
            if ($status == 'cancelled') $cancelled_count++;
        }
        $telehealth_result->data_seek(0);
    }
    ?>
    
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-number"><?php echo $total_in_person + $total_telehealth; ?></div>
            <div class="stat-label">Total Appointments</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-number" style="color: #856404;"><?php echo $pending_count; ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number" style="color: #155724;"><?php echo $confirmed_count; ?></div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✔️</div>
            <div class="stat-number" style="color: #004085;"><?php echo $completed_count; ?></div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">❌</div>
            <div class="stat-number" style="color: #721c24;"><?php echo $cancelled_count; ?></div>
            <div class="stat-label">Cancelled</div>
        </div>
    </div>
    
    <div class="impersonation-panel">
        <div class="impersonation-header">
            <div>
                <h3><i class="fas fa-user-switch"></i> Patient View Mode</h3>
                <p>Switch to patient view to see what patients see</p>
            </div>
            <?php if (isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true): ?>
                <a href="admin_stop_impersonate.php" class="stop-impersonate-btn"><i class="fas fa-times-circle"></i> Exit Patient View</a>
            <?php else: ?>
                <a href="admin_impersonate_self.php" class="impersonate-self-btn" onclick="return confirm('Switch to Patient View?\n\nYou will see your account as a patient would. Click \"Exit Patient View\" to return to admin mode.');"><i class="fas fa-eye"></i> View as Patient</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="appointments-section">
        <?php if (isset($success)): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- In-Person Appointments -->
        <h2 class="section-title"><i class="fas fa-hospital-user"></i> In-Person Appointments</h2>
        <div class="appointment-table">
            <table>
                <thead>
                    <tr><th>ID</th><th>Patient Name</th><th>Email</th><th>Date</th><th>Time</th><th>Department</th><th>Doctor</th><th>Reason</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if ($in_person_result && $in_person_result->num_rows > 0): ?>
                        <?php while ($row = $in_person_result->fetch_assoc()): ?>
                            <?php 
                            $status = $row['status'] ?? 'pending';
                            $status_class = '';
                            switch($status) {
                                case 'confirmed': $status_class = 'status-confirmed'; break;
                                case 'completed': $status_class = 'status-completed'; break;
                                case 'cancelled': $status_class = 'status-cancelled'; break;
                                default: $status_class = 'status-pending';
                            }
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $row['department'] ?? 'N/A')); ?></td>
                                <td class="doctor-name"><?php echo htmlspecialchars($row['doctor'] ?? 'N/A'); ?></td>
                                <td><?php echo substr(htmlspecialchars($row['reason'] ?? ''), 0, 40); ?>...</td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span></td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="appointment_type" value="in_person">
                                        <select name="status">
                                            <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo ($status == 'confirmed') ? 'selected' : ''; ?>>Confirm</option>
                                            <option value="completed" <?php echo ($status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo ($status == 'cancelled') ? 'selected' : ''; ?>>Cancel</option>
                                        </select>
                                        <button type="submit" name="update_status" class="update-btn"><i class="fas fa-save"></i> Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="10" class="no-data"><i class="fas fa-inbox"></i> No in-person appointments found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Telehealth Appointments -->
        <h2 class="section-title"><i class="fas fa-video"></i> Telehealth Appointments</h2>
        <div class="appointment-table">
            <table>
                <thead>
                    <tr><th>ID</th><th>Patient Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Time</th><th>Reason</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if ($telehealth_result && $telehealth_result->num_rows > 0): ?>
                        <?php while ($row = $telehealth_result->fetch_assoc()): ?>
                            <?php 
                            $status = $row['status'] ?? 'pending';
                            $status_class = '';
                            switch($status) {
                                case 'confirmed': $status_class = 'status-confirmed'; break;
                                case 'completed': $status_class = 'status-completed'; break;
                                case 'cancelled': $status_class = 'status-cancelled'; break;
                                default: $status_class = 'status-pending';
                            }
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo substr(htmlspecialchars($row['reason'] ?? ''), 0, 40); ?>...</td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span></td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="appointment_type" value="telehealth">
                                        <select name="status">
                                            <option value="pending" <?php echo ($status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo ($status == 'confirmed') ? 'selected' : ''; ?>>Confirm</option>
                                            <option value="completed" <?php echo ($status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo ($status == 'cancelled') ? 'selected' : ''; ?>>Cancel</option>
                                        </select>
                                        <button type="submit" name="update_status" class="update-btn"><i class="fas fa-save"></i> Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="no-data"><i class="fas fa-inbox"></i> No telehealth appointments found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Quick Links -->
        <div class="quick-links">
            <a href="admin_medical_requests.php" class="quick-link-btn"><i class="fas fa-folder-medical"></i> Medical Record Requests</a>
            <a href="admin_feedback.php" class="quick-link-btn"><i class="fas fa-comment-dots"></i> Patient Feedback</a>
            <a href="admin_contact_messages.php" class="quick-link-btn"><i class="fas fa-envelope"></i> Contact Messages</a>
            <a href="admin_chat.php" class="quick-link-btn <?php echo ($active_chats > 0 || $unread_messages > 0) ? 'chat-badge-link' : ''; ?>">
                <i class="fas fa-comments"></i> Live Chat
                <?php if ($unread_messages > 0): ?>
                    <span class="active-chats-badge"><?php echo $unread_messages; ?> new</span>
                <?php elseif ($active_chats > 0): ?>
                    <span class="active-chats-badge"><?php echo $active_chats; ?> active</span>
                <?php endif; ?>
            </a>
            <a href="admin_applications.php" class="quick-link-btn applications-link">
                <i class="fas fa-users"></i> Nurse Applications
                <?php if ($pending_applications > 0): ?>
                    <span class="active-chats-badge"><?php echo $pending_applications; ?> pending</span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</body>
</html>