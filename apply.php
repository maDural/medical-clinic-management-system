<?php
session_start();
include 'includes/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_submit'])) {
    // Get form data
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Handle file upload
    $upload_success = false;
    $file_name = '';
    
    if (isset($_FILES['diploma']) && $_FILES['diploma']['error'] == 0) {
        $upload_dir = 'uploads/applications/';
        
        // Create directory if not exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['diploma']['name']);
        $file_tmp = $_FILES['diploma']['tmp_name'];
        $file_size = $_FILES['diploma']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        
        if (in_array($file_ext, $allowed_ext) && $file_size <= 5000000) { // 5MB max
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $upload_success = true;
            }
        }
    }
    
    // Insert into database
    $query = "INSERT INTO nurse_applications (fullname, email, phone, qualification, experience, message, diploma_file, status, created_at) 
              VALUES ('$fullname', '$email', '$phone', '$qualification', '$experience', '$message', '$file_name', 'pending', NOW())";
    
    if ($conn->query($query)) {
        $success_message = "✅ Your application has been submitted successfully! We will review your application and contact you within 5-7 business days.";
    } else {
        $error_message = "❌ Error submitting application: " . $conn->error;
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Now - Florida Medical Clinic</title>
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

        .apply-hero {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            margin-bottom: 40px;
        }

        .apply-hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .apply-hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.95;
        }

        .container {
            max-width: 800px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }

        .apply-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .apply-card h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .apply-card .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 22px;
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

        .required {
            color: #e74c3c;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f6;
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #f093fb;
            background: white;
            box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input[type="file"] {
            padding: 12px;
            cursor: pointer;
        }

        .file-info {
            font-size: 12px;
            color: #888;
            margin-top: 6px;
        }

        .file-info i {
            color: #f093fb;
        }

        .submit-btn {
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

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            border-left: 4px solid #dc3545;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
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

        .application-note {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 3px solid #f093fb;
        }

        .application-note i {
            color: #f093fb;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .apply-hero h1 {
                font-size: 32px;
            }
            .apply-card {
                padding: 25px;
            }
            .apply-card h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .apply-card {
                padding: 20px;
            }
            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="apply-hero">
    <div class="container">
        <h1>Apply Now</h1>
        <p>Join Florida Medical Clinic and become part of a healthcare revolution</p>
    </div>
</div>

<div class="container">
    <div class="apply-card">
        <h2>Nurse Application Form</h2>
        <p class="subtitle">Complete the form below to apply for the Professional Nurse Program</p>

        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                <input type="text" name="fullname" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                <input type="email" name="email" placeholder="Enter your email address" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone-alt"></i> Phone Number <span class="required">*</span></label>
                <input type="tel" name="phone" placeholder="Enter your phone number" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-graduation-cap"></i> Qualification <span class="required">*</span></label>
                <select name="qualification" required>
                    <option value="">Select your qualification</option>
                    <option value="diploma_primary_healthcare">Diploma in Primary Healthcare</option>
                    <option value="degree_nursing">Degree in Nursing</option>
                    <option value="postgraduate_diploma">Postgraduate Diploma in Primary Healthcare</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-clock"></i> Years of Experience <span class="required">*</span></label>
                <select name="experience" required>
                    <option value="">Select years of experience</option>
                    <option value="5-10">5 - 10 years</option>
                    <option value="10-15">10 - 15 years</option>
                    <option value="15-20">15 - 20 years</option>
                    <option value="20+">20+ years</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-comment"></i> Additional Message</label>
                <textarea name="message" placeholder="Tell us why you want to join our program and any additional information..."></textarea>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-upload"></i> Upload Primary Healthcare Diploma <span class="required">*</span></label>
                <input type="file" name="diploma" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                <div class="file-info">
                    <i class="fas fa-info-circle"></i> Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB)
                </div>
            </div>

            <button type="submit" name="apply_submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Submit Application
            </button>
        </form>

        <div class="application-note">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>What happens next?</strong> We will review your application within 5-7 business days. 
                You will be contacted via email or phone for the next steps in the process.
            </div>
        </div>

        <div class="back-link">
            <a href="nurses.php">
                <i class="fas fa-arrow-left"></i> Back to Nurses Page
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>