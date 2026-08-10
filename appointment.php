<?php
session_start();
include 'includes/db.php';

// protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info for personalized greeting
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT fullname, email FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();
$user_name = $user['fullname'];
$user_email = $user['email'];

$success = '';
$error = '';

if (isset($_POST['book'])) {
    $appointment_type = mysqli_real_escape_string($conn, $_POST['appointment_type']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $user_id = $_SESSION['user_id'];
    
    if ($appointment_type == 'in_person') {
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $doctor = mysqli_real_escape_string($conn, $_POST['doctor']);
        
        $query = "INSERT INTO appointments (user_id, fullname, email, appointment_type, appointment_date, appointment_time, department, doctor, reason, status, created_at) 
                  VALUES ('$user_id', '$user_name', '$user_email', 'in_person', '$date', '$time', '$department', '$doctor', '$reason', 'pending', NOW())";
        
        if ($conn->query($query)) {
            $success = "✅ In-person appointment booked successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    } 
    elseif ($appointment_type == 'telehealth') {
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $video_link = "https://teams.floridamedical.com/meet/" . uniqid() . "_" . $user_id;
        
        $query = "INSERT INTO telehealth_appointments (user_id, fullname, email, phone, appointment_date, appointment_time, reason, video_link, status, created_at) 
                  VALUES ('$user_id', '$user_name', '$user_email', '$phone', '$date', '$time', '$reason', '$video_link', 'pending', NOW())";
        
        if ($conn->query($query)) {
            $success = "✅ Telehealth appointment booked successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Florida Medical Clinic</title>
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

        /* Form Container */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }

        /* Form Box - Glassmorphism Effect */
        .form-box {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 560px;
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
            backdrop-filter: blur(10px);
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
            padding: 35px;
        }

        .form-box h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-message {
            text-align: center;
            color: #667eea;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Success/Error Messages */
        .success, .error {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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

        /* Appointment Type Cards */
        .appointment-type {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .type-option {
            flex: 1;
            text-align: center;
            padding: 18px 12px;
            border: 2px solid #eef2f6;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .type-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateY(-3px);
        }

        .type-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
        }

        .type-icon {
            font-size: 36px;
            display: block;
            margin-bottom: 8px;
        }

        .type-title {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 15px;
        }

        .type-desc {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }

        /* Form Elements */
        input, select, textarea {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 18px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Conditional Fields */
        .conditional-fields {
            display: none;
        }

        .conditional-fields.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Time Slots */
        .quick-times {
            margin-bottom: 18px;
        }

        .quick-times label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 13px;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .time-slot {
            padding: 10px 5px;
            background: #f0f2f5;
            text-align: center;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
        }

        .time-slot:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .time-slot.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .custom-time-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 12px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Info Boxes */
        .info-box {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 14px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
            color: #666;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            background: #f0f2f5;
            transform: translateX(5px);
        }

        .info-icon {
            font-size: 24px;
        }

        .info-text strong {
            color: #1a1a2e;
            display: block;
            margin-bottom: 4px;
        }

        /* Telehealth Info */
        .telehealth-info {
            background: #e8f4f8;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 12px;
            border-left: 3px solid #667eea;
        }

        .telehealth-info strong {
            color: #004085;
        }

        /* Decorative Elements */
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
                padding: 25px;
            }
            
            .time-slots {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .appointment-type {
                flex-direction: column;
                gap: 12px;
            }
            
            .top-bar {
                padding: 12px 20px;
            }
            
            .top-bar h2 {
                font-size: 18px;
            }
            
            .back-btn, .logout-btn {
                padding: 6px 14px;
                font-size: 12px;
            }
        }

        @media (max-width: 450px) {
            .time-slots {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<!-- Decorative background element -->
<div class="bg-decoration">
    <i class="fas fa-heartbeat"></i>
</div>

<div class="top-bar">
    <h2>
        <i class="fas fa-calendar-plus"></i>
        Book Appointment
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

<div class="form-container">
    <div class="form-box">
        <div class="form-inner">
            <h2>Schedule Your Visit</h2>
            <div class="welcome-message">
                <i class="fas fa-hand-wave"></i> Welcome back, <?php echo htmlspecialchars($user_name); ?>!
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

            <form method="POST" id="appointmentForm">
                <!-- Appointment Type Selection -->
                <div class="appointment-type">
                    <div class="type-option selected" data-type="in_person">
                        <div class="type-icon">🏥</div>
                        <div class="type-title">In-Person Visit</div>
                        <div class="type-desc">Visit us at our clinic</div>
                    </div>
                    <div class="type-option" data-type="telehealth">
                        <div class="type-icon">💻</div>
                        <div class="type-title">Telehealth Visit</div>
                        <div class="type-desc">Video consultation from home</div>
                    </div>
                </div>

                <input type="hidden" name="appointment_type" id="appointment_type" value="in_person">

                <!-- Date Input -->
                <input type="date" name="date" id="appointmentDate" placeholder="Select Date" required>

                <!-- Time Selection -->
                <div class="quick-times">
                    <label><i class="far fa-clock"></i> Select Time</label>
                    <div class="time-slots">
                        <div class="time-slot" data-time="09:00">9:00 AM</div>
                        <div class="time-slot" data-time="10:00">10:00 AM</div>
                        <div class="time-slot" data-time="11:00">11:00 AM</div>
                        <div class="time-slot" data-time="12:00">12:00 PM</div>
                        <div class="time-slot" data-time="13:00">1:00 PM</div>
                        <div class="time-slot" data-time="14:00">2:00 PM</div>
                        <div class="time-slot" data-time="15:00">3:00 PM</div>
                        <div class="time-slot" data-time="16:00">4:00 PM</div>
                    </div>
                    <div class="custom-time-label">Or enter custom time:</div>
                    <input type="time" name="time" id="appointmentTime" step="1800">
                </div>

                <!-- In-Person Fields -->
                <div id="inPersonFields" class="conditional-fields active">
                    <select name="department" id="department" required>
                        <option value="">Select Department</option>
                        <option value="primary_care">🏥 Primary Care</option>
                        <option value="cardiology">❤️ Cardiology</option>
                        <option value="pediatrics">👶 Pediatrics</option>
                        <option value="womens_health">👩 Women's Health</option>
                        <option value="neurology">🧠 Neurology</option>
                        <option value="orthopedics">🦴 Orthopedics</option>
                        <option value="dental">🦷 Dental Care</option>
                    </select>

                    <select name="doctor" id="doctor" required>
                        <option value="">Select Doctor</option>
                        <option value="Dr M.Ndlovu">👨‍⚕️ Dr. Mthetheleli Ndlovu - Primary Care</option>
                        <option value="Dr N.Phungula">👩‍⚕️ Dr. Nompumelelo Phungula - Cardiology</option>
                        <option value="Dr L.Mgaga">👨‍⚕️ Dr. Likhona Mgaga - Pediatrics</option>
                        <option value="Dr S.Ngobese">👨‍⚕️ Dr. Sebenza Ngobese - Surgery</option>
                        <option value="Dr A.Phungula">👩‍⚕️ Dr. Amahle Phungula - Women's Health</option>
                        <option value="Dr S.Gabuza">👨‍⚕️ Dr. Silindile Gabuza - Neurology</option>
                        <option value="Dr M.Malinga">👨‍⚕️ Dr. Mandla Malinga - Dental</option>
                    </select>
                </div>

                <!-- Telehealth Fields -->
                <div id="telehealthFields" class="conditional-fields">
                    <input type="tel" name="phone" id="phone" placeholder="📞 Phone Number">
                    <div class="telehealth-info">
                        <strong><i class="fas fa-video"></i> How Telehealth Works:</strong><br>
                        You will receive a secure video link via email before your appointment.
                    </div>
                </div>

                <!-- Reason -->
                <textarea name="reason" placeholder="📝 Reason for visit / Symptoms" required></textarea>

                <!-- Submit Button -->
                <button type="submit" name="book" class="submit-btn">
                    <i class="fas fa-check-circle"></i> Book Appointment
                </button>

                <!-- Info Boxes -->
                <div class="info-box">
                    <div class="info-icon">ℹ️</div>
                    <div class="info-text">
                        <strong>Need to reschedule?</strong>
                        Please call us at least 24 hours in advance to avoid cancellation fees.
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <strong>Location:</strong>
                        123 Medical Drive, Florida, FL 33101
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('appointmentDate').setAttribute('min', today);

    const typeOptions = document.querySelectorAll('.type-option');
    const appointmentTypeInput = document.getElementById('appointment_type');
    const inPersonFields = document.getElementById('inPersonFields');
    const telehealthFields = document.getElementById('telehealthFields');
    const departmentSelect = document.getElementById('department');
    const doctorSelect = document.getElementById('doctor');
    const phoneInput = document.getElementById('phone');

    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            typeOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            const type = this.dataset.type;
            appointmentTypeInput.value = type;
            
            if (type === 'in_person') {
                inPersonFields.classList.add('active');
                telehealthFields.classList.remove('active');
                if (departmentSelect) departmentSelect.required = true;
                if (doctorSelect) doctorSelect.required = true;
                if (phoneInput) phoneInput.required = false;
            } else {
                inPersonFields.classList.remove('active');
                telehealthFields.classList.add('active');
                if (departmentSelect) departmentSelect.required = false;
                if (doctorSelect) doctorSelect.required = false;
                if (phoneInput) phoneInput.required = true;
            }
        });
    });

    const timeSlots = document.querySelectorAll('.time-slot');
    const timeInput = document.getElementById('appointmentTime');

    timeSlots.forEach(slot => {
        slot.addEventListener('click', function() {
            timeSlots.forEach(s => s.classList.remove('selected'));
            this.classList.add('selected');
            timeInput.value = this.dataset.time;
        });
    });

    timeInput.addEventListener('input', function() {
        timeSlots.forEach(slot => slot.classList.remove('selected'));
    });

    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        const date = document.getElementById('appointmentDate').value;
        const time = timeInput.value;
        const reason = document.querySelector('textarea[name="reason"]').value;
        const appointmentType = appointmentTypeInput.value;

        if (!date || !time || !reason) {
            e.preventDefault();
            alert('Please fill in all fields: Date, Time, and Reason for visit.');
            return;
        }

        if (appointmentType === 'in_person') {
            const department = departmentSelect ? departmentSelect.value : '';
            const doctor = doctorSelect ? doctorSelect.value : '';
            if (!department || !doctor) {
                e.preventDefault();
                alert('Please select both Department and Doctor for your in-person appointment.');
            }
        } else {
            const phone = phoneInput ? phoneInput.value : '';
            if (!phone) {
                e.preventDefault();
                alert('Please enter your phone number for the telehealth appointment.');
            }
        }
    });
</script>

</body>
</html>