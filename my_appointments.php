<?php
session_start();
include 'includes/db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user name for welcome message
$user_query = $conn->query("SELECT fullname FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];

// Fetch appointments for the logged-in user
$query = "SELECT * FROM appointments WHERE user_id = '$user_id' ORDER BY appointment_date DESC";
$result = $conn->query($query);

// Fetch telehealth appointments
$telehealth_query = "SELECT * FROM telehealth_appointments WHERE user_id = '$user_id' ORDER BY appointment_date DESC";
$telehealth_result = $conn->query($telehealth_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Florida Medical Clinic</title>
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
            /* Modern medical background with overlay */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }
        
        /* Alternative: You can use a local image instead */
        /* background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('appointment-bg.jpg') no-repeat center center fixed;
        background-size: cover; */

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
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Welcome Card */
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            animation: fadeInUp 0.5s ease;
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

        .welcome-card h1 {
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-card p {
            color: #666;
            font-size: 14px;
        }

        /* Appointments Section */
        .appointments-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease;
            backdrop-filter: blur(5px);
        }

        .appointments-section h2 {
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        /* Appointment Card */
        .appointment-card {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .appointment-card:hover {
            transform: translateX(8px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            background: white;
        }

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
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
            font-weight: 500;
            font-size: 14px;
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

        /* Status Message */
        .status-message {
            font-size: 13px;
            margin-top: 15px;
            padding: 12px 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-message.pending {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid #856404;
        }

        .status-message.confirmed {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid #155724;
        }

        .status-message.completed {
            background: #cce5ff;
            color: #004085;
            border-left: 3px solid #004085;
        }

        .status-message.cancelled {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #721c24;
        }

        .status-message i {
            font-size: 18px;
        }

        /* No Appointments */
        .no-appointments {
            text-align: center;
            padding: 50px;
            color: #888;
        }

        .no-appointments i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .book-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Video Call Button */
        .video-call-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #28a745;
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .video-call-btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
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

            .appointments-section {
                padding: 20px;
            }

            .appointment-details {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                flex-direction: column;
            }

            .action-btn {
                justify-content: center;
            }

            .welcome-card h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<!-- Decorative background element -->
<div class="bg-decoration">
    <i class="fas fa-calendar-check"></i>
</div>

<div class="top-bar">
    <h2>
        <i class="fas fa-calendar-alt"></i>
        My Appointments
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
    <!-- Welcome Card -->
    <div class="welcome-card">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
        <p>Track and manage all your appointments in one place</p>
    </div>
    
    <!-- In-Person Appointments -->
    <div class="appointments-section">
        <h2>
            <i class="fas fa-hospital-user"></i> In-Person Appointments
        </h2>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                $status = isset($row['status']) ? $row['status'] : 'pending';
                
                $status_class = '';
                $status_message = '';
                $status_icon = '';
                switch($status) {
                    case 'confirmed':
                        $status_class = 'status-confirmed';
                        $status_message = 'Your appointment has been confirmed! Please arrive 15 minutes early.';
                        $status_icon = 'fa-check-circle';
                        break;
                    case 'completed':
                        $status_class = 'status-completed';
                        $status_message = 'This appointment has been completed. Thank you for choosing us!';
                        $status_icon = 'fa-check-double';
                        break;
                    case 'cancelled':
                        $status_class = 'status-cancelled';
                        $status_message = 'This appointment has been cancelled. Please book a new one if needed.';
                        $status_icon = 'fa-times-circle';
                        break;
                    default:
                        $status_class = 'status-pending';
                        $status = 'pending';
                        $status_message = 'Your appointment is pending admin approval. You will receive confirmation once approved.';
                        $status_icon = 'fa-clock';
                }
                ?>
                <div class="appointment-card">
                    <div class="appointment-details">
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                            <span class="detail-label">Date:</span>
                            <span class="detail-value"><?php echo date('F d, Y', strtotime($row['appointment_date'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-clock"></i></div>
                            <span class="detail-label">Time:</span>
                            <span class="detail-value"><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></span>
                        </div>
                        <?php if (isset($row['department']) && $row['department']): ?>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-building"></i></div>
                            <span class="detail-label">Department:</span>
                            <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($row['department']))); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($row['doctor']) && $row['doctor']): ?>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-user-md"></i></div>
                            <span class="detail-label">Doctor:</span>
                            <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($row['doctor']))); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fas fa-tag"></i></div>
                            <span class="detail-label">Status:</span>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </div>
                    </div>
                    <?php if (isset($row['reason']) && $row['reason']): ?>
                    <div class="detail-item" style="margin-top: 5px;">
                        <div class="detail-icon"><i class="fas fa-comment"></i></div>
                        <span class="detail-label">Reason:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($row['reason']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="status-message <?php echo $status; ?>">
                        <i class="fas <?php echo $status_icon; ?>"></i>
                        <?php echo $status_message; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-appointments">
                <i class="fas fa-calendar-times"></i>
                <p>No in-person appointments found.</p>
                <a href="appointment.php" class="book-btn">
                    <i class="fas fa-plus-circle"></i> Book an Appointment
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Telehealth Appointments -->
    <?php if ($telehealth_result && $telehealth_result->num_rows > 0): ?>
    <div class="appointments-section">
        <h2>
            <i class="fas fa-video"></i> Telehealth Appointments
        </h2>
        
        <?php while ($row = $telehealth_result->fetch_assoc()): ?>
            <?php 
            $status = isset($row['status']) ? $row['status'] : 'pending';
            
            $status_class = '';
            $status_message = '';
            $status_icon = '';
            switch($status) {
                case 'confirmed':
                    $status_class = 'status-confirmed';
                    $status_message = 'Your telehealth appointment has been confirmed! Click the button below to join.';
                    $status_icon = 'fa-check-circle';
                    break;
                case 'completed':
                    $status_class = 'status-completed';
                    $status_message = 'This telehealth appointment has been completed.';
                    $status_icon = 'fa-check-double';
                    break;
                case 'cancelled':
                    $status_class = 'status-cancelled';
                    $status_message = 'This telehealth appointment has been cancelled.';
                    $status_icon = 'fa-times-circle';
                    break;
                default:
                    $status_class = 'status-pending';
                    $status = 'pending';
                    $status_message = 'Your telehealth appointment is pending admin approval. You will receive a video link once confirmed.';
                    $status_icon = 'fa-clock';
            }
            ?>
            <div class="appointment-card">
                <div class="appointment-details">
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                        <span class="detail-label">Date:</span>
                        <span class="detail-value"><?php echo date('F d, Y', strtotime($row['appointment_date'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-clock"></i></div>
                        <span class="detail-label">Time:</span>
                        <span class="detail-value"><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-laptop-medical"></i></div>
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">Virtual Consultation</span>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-tag"></i></div>
                        <span class="detail-label">Status:</span>
                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo ucfirst($status); ?>
                        </span>
                    </div>
                </div>
                <?php if (isset($row['reason']) && $row['reason']): ?>
                <div class="detail-item" style="margin-top: 5px;">
                    <div class="detail-icon"><i class="fas fa-comment"></i></div>
                    <span class="detail-label">Reason:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['reason']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="status-message <?php echo $status; ?>">
                    <i class="fas <?php echo $status_icon; ?>"></i>
                    <?php echo $status_message; ?>
                </div>
                
                <?php if ($status == 'confirmed'): ?>
                <div style="margin-top: 15px;">
                    <a href="#" class="video-call-btn" onclick="alert('Video link will be sent to your email before the appointment.'); return false;">
                        <i class="fas fa-video"></i> Join Video Call
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
    
    <!-- Quick Action Buttons -->
    <div class="quick-actions">
        <a href="appointment.php" class="action-btn primary">
            <i class="fas fa-plus-circle"></i> Book New Appointment
        </a>
        <a href="dashboard.php" class="action-btn secondary">
            <i class="fas fa-tachometer-alt"></i> Go to Dashboard
        </a>
    </div>
</div>

</body>
</html>