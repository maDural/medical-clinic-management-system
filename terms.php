<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Florida Medical Clinic</title>
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

        /* Hero Section */
        .terms-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .terms-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .terms-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .terms-hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Container */
        .container {
            max-width: 1100px;
            margin: 40px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* Terms Content */
        .terms-content {
            background: white;
            padding: 50px;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 50px;
            animation: fadeInUp 0.6s ease;
        }

        /* Terms Section */
        .terms-section {
            margin-bottom: 40px;
            padding-bottom: 10px;
        }

        .terms-section:last-child {
            margin-bottom: 0;
        }

        .terms-section h2 {
            color: #1a1a2e;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .terms-section h2 i {
            margin-right: 10px;
            color: #667eea;
        }

        .terms-section h3 {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 12px;
        }

        .terms-section h3 i {
            margin-right: 8px;
        }

        .terms-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .terms-section ul {
            margin: 15px 0 15px 25px;
            color: #555;
            line-height: 1.8;
        }

        .terms-section li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .terms-section li strong {
            color: #1a1a2e;
        }

        /* Acceptance Box */
        .acceptance {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin-top: 30px;
            border-left: 4px solid #667eea;
        }

        .acceptance p {
            color: #555;
            font-size: 14px;
            line-height: 1.7;
        }

        .acceptance strong {
            color: #1a1a2e;
        }

        .last-updated {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .last-updated i {
            color: #667eea;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .terms-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .terms-hero h1 {
                font-size: 32px;
            }

            .terms-hero p {
                font-size: 16px;
            }

            .terms-content {
                padding: 30px;
            }

            .terms-section h2 {
                font-size: 20px;
            }

            .terms-section h3 {
                font-size: 16px;
            }

            .terms-section p,
            .terms-section li {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .terms-content {
                padding: 25px;
            }

            .terms-section h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="terms-hero">
    <h1>Terms of Service</h1>
    <p>Please read these terms carefully before using our services</p>
</div>

<div class="container">
    <div class="terms-content">
        <div class="terms-section">
            <h2><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h2>
            <p>By accessing or using the Florida Medical Clinic website, patient portal, or services, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-stethoscope"></i> 2. Medical Disclaimer</h2>
            <p>The information provided on this website is for informational purposes only and is not a substitute for professional medical advice. Always seek the advice of your physician or other qualified health provider with any questions you may have regarding a medical condition.</p>
            <ul>
                <li>Do not disregard professional medical advice based on information from this website</li>
                <li>If you have a medical emergency, call 911 immediately</li>
                <li>We do not guarantee the accuracy or completeness of any information on this site</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-user-lock"></i> 3. Patient Portal Terms</h2>
            <p>When using our patient portal, you agree to:</p>
            <ul>
                <li>Provide accurate and current information</li>
                <li>Maintain the security of your login credentials</li>
                <li>Notify us immediately of any unauthorized access</li>
                <li>Use the portal only for legitimate healthcare purposes</li>
                <li>Not share your account with others</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-calendar-check"></i> 4. Appointment Policies</h2>
            <h3><i class="fas fa-ban"></i> Cancellation Policy</h3>
            <p>Please provide at least 24 hours notice for appointment cancellations or rescheduling. Late cancellations or no-shows may result in a fee.</p>
            
            <h3><i class="fas fa-clock"></i> Late Arrival Policy</h3>
            <p>If you arrive more than 15 minutes late, we may need to reschedule your appointment to accommodate other patients.</p>
            
            <h3><i class="fas fa-video"></i> Telehealth Appointments</h3>
            <p>You must have a stable internet connection and be in a private location. We are not responsible for technical issues on your end.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-credit-card"></i> 5. Payment and Billing</h2>
            <p>You agree to:</p>
            <ul>
                <li>Pay all fees associated with your medical care</li>
                <li>Provide accurate insurance information</li>
                <li>Pay any copays, deductibles, or non-covered services at the time of service</li>
                <li>Cooperate with our billing department regarding payment arrangements</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-laptop-medical"></i> 6. Telehealth Services</h2>
            <p>Telehealth services are subject to additional terms:</p>
            <ul>
                <li>Not all conditions can be treated via telehealth</li>
                <li>You may be referred for an in-person visit if necessary</li>
                <li>Telehealth visits are recorded for quality and safety purposes</li>
                <li>You must be physically located in a state where we are licensed to practice</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-gavel"></i> 7. User Conduct</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Use the website for any illegal purpose</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Transmit viruses or malicious code</li>
                <li>Harass, abuse, or threaten staff or other patients</li>
                <li>Post false or misleading information</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-copyright"></i> 8. Intellectual Property</h2>
            <p>All content on this website, including text, graphics, logos, and software, is the property of Florida Medical Clinic and is protected by copyright laws. You may not reproduce, distribute, or modify any content without our written permission.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-exclamation-triangle"></i> 9. Limitation of Liability</h2>
            <p>To the maximum extent permitted by law, Florida Medical Clinic shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our services.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-edit"></i> 10. Modification of Terms</h2>
            <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of our services constitutes acceptance of the modified terms.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-balance-scale"></i> 11. Governing Law</h2>
            <p>These terms shall be governed by and construed in accordance with the laws of the State of Florida, without regard to its conflict of law provisions.</p>
        </div>
        
        <div class="terms-section">
            <h2><i class="fas fa-envelope"></i> 12. Contact Information</h2>
            <p>For questions about these Terms of Service, please contact us:</p>
            <ul>
                <li><strong><i class="fas fa-envelope"></i> Email:</strong> mthethelelin7@gmail.com</li>
                <li><strong><i class="fas fa-phone-alt"></i> Phone:</strong> +27 72 202 1793</li>
                <li><strong><i class="fas fa-map-marker-alt"></i> Address:</strong> R33 Dundee Main Road, Tugela Ferry, 3010</li>
            </ul>
        </div>
        
        <div class="acceptance">
            <p><i class="fas fa-check-circle" style="color: #28a745;"></i> By using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
        </div>
        
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>