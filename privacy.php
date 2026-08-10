<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Florida Medical Clinic</title>
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
        .policy-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .policy-hero::before {
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

        .policy-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .policy-hero p {
            font-size: 18px;
            max-width: 700px;
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

        /* Policy Content */
        .policy-content {
            background: white;
            padding: 50px;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 50px;
            animation: fadeInUp 0.6s ease;
        }

        /* Policy Section */
        .policy-section {
            margin-bottom: 40px;
            padding-bottom: 10px;
        }

        .policy-section:last-child {
            margin-bottom: 0;
        }

        .policy-section h2 {
            color: #1a1a2e;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .policy-section h2 i {
            margin-right: 10px;
            color: #667eea;
        }

        .policy-section h3 {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 12px;
        }

        .policy-section h3 i {
            margin-right: 8px;
        }

        .policy-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .policy-section ul {
            margin: 15px 0 15px 25px;
            color: #555;
            line-height: 1.8;
        }

        .policy-section li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .policy-section li strong {
            color: #1a1a2e;
        }

        /* Last Updated */
        .last-updated {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 18px;
            border-radius: 16px;
            text-align: center;
            color: #666;
            font-size: 13px;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .last-updated i {
            color: #667eea;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .policy-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .policy-hero h1 {
                font-size: 32px;
            }

            .policy-hero p {
                font-size: 16px;
            }

            .policy-content {
                padding: 30px;
            }

            .policy-section h2 {
                font-size: 20px;
            }

            .policy-section h3 {
                font-size: 16px;
            }

            .policy-section p,
            .policy-section li {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .policy-content {
                padding: 25px;
            }

            .policy-section h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="policy-hero">
    <h1>Privacy Policy</h1>
    <p>Your privacy and trust are important to us. Learn how we protect your information.</p>
</div>

<div class="container">
    <div class="policy-content">
        <div class="policy-section">
            <h2><i class="fas fa-database"></i> 1. Information We Collect</h2>
            <p>At Florida Medical Clinic, we collect information to provide better healthcare services. The types of information we collect include:</p>
            <ul>
                <li><strong><i class="fas fa-user"></i> Personal Information:</strong> Name, date of birth, address, phone number, email address, and social security number.</li>
                <li><strong><i class="fas fa-notes-medical"></i> Medical Information:</strong> Health history, current medications, allergies, test results, and treatment records.</li>
                <li><strong><i class="fas fa-file-invoice"></i> Insurance Information:</strong> Insurance provider details, policy numbers, and payment information.</li>
                <li><strong><i class="fas fa-laptop-code"></i> Technical Information:</strong> IP addresses, browser types, and usage data when using our patient portal.</li>
            </ul>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-chart-line"></i> 2. How We Use Your Information</h2>
            <p>We use your information to provide quality healthcare services, including:</p>
            <ul>
                <li>Diagnosing and treating medical conditions</li>
                <li>Processing insurance claims and payments</li>
                <li>Scheduling appointments and sending reminders</li>
                <li>Communicating with you about your health</li>
                <li>Improving our services and patient experience</li>
                <li>Complying with legal and regulatory requirements</li>
            </ul>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-shield-alt"></i> 3. HIPAA Compliance</h2>
            <p>We are fully compliant with the Health Insurance Portability and Accountability Act (HIPAA). This means:</p>
            <ul>
                <li>Your medical records are confidential and protected</li>
                <li>You have the right to access and request copies of your records</li>
                <li>We obtain your authorization before sharing information except for treatment, payment, or operations</li>
                <li>We maintain strict security measures to protect your data</li>
            </ul>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-share-alt"></i> 4. Information Sharing</h2>
            <p>We may share your information in the following circumstances:</p>
            <ul>
                <li><strong>Treatment:</strong> With other healthcare providers involved in your care</li>
                <li><strong>Payment:</strong> With insurance companies for claims processing</li>
                <li><strong>Healthcare Operations:</strong> For quality assessment and improvement</li>
                <li><strong>Legal Requirements:</strong> When required by law or court order</li>
                <li><strong>Your Authorization:</strong> With your written permission</li>
            </ul>
            <p><strong>We never sell your personal information to third parties.</strong></p>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-lock"></i> 5. Data Security</h2>
            <p>We implement robust security measures to protect your information:</p>
            <ul>
                <li>Encrypted electronic health records</li>
                <li>Secure patient portal with two-factor authentication</li>
                <li>Regular security audits and staff training</li>
                <li>Physical security at our facilities</li>
                <li>Secure destruction of records when required</li>
            </ul>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-gavel"></i> 6. Your Rights</h2>
            <p>You have the following rights regarding your health information:</p>
            <ul>
                <li>Right to access and obtain copies of your records</li>
                <li>Right to request amendments to your records</li>
                <li>Right to request restrictions on certain uses of your information</li>
                <li>Right to receive confidential communications</li>
                <li>Right to an accounting of disclosures</li>
                <li>Right to file a complaint with us or the Department of Health and Human Services</li>
            </ul>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-cookie-bite"></i> 7. Cookies and Tracking</h2>
            <p>Our website uses cookies to improve your experience. We use:</p>
            <ul>
                <li><strong>Essential Cookies:</strong> Required for website functionality</li>
                <li><strong>Analytics Cookies:</strong> To understand how visitors use our site</li>
                <li><strong>Preference Cookies:</strong> To remember your settings</li>
            </ul>
            <p>You can adjust your browser settings to refuse cookies, but some features may not function properly.</p>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-child"></i> 8. Children's Privacy</h2>
            <p>We collect information from children only with parental consent. Parents have full access to their child's records and can request corrections or deletions as permitted by law.</p>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-edit"></i> 9. Changes to This Policy</h2>
            <p>We may update this privacy policy periodically. Any changes will be posted on this page with the effective date. We encourage you to review this policy regularly.</p>
        </div>
        
        <div class="policy-section">
            <h2><i class="fas fa-envelope"></i> 10. Contact Us</h2>
            <p>If you have questions about this privacy policy or your health information rights, please contact:</p>
            <ul>
                <li><strong><i class="fas fa-user-shield"></i> Privacy Officer:</strong> Florida Medical Clinic</li>
                <li><strong><i class="fas fa-phone-alt"></i> Phone:</strong> (555) 123-4567</li>
                <li><strong><i class="fas fa-envelope"></i> Email:</strong> privacy@floridamedical.com</li>
                <li><strong><i class="fas fa-map-marker-alt"></i> Address:</strong> 123 Medical Drive, Florida, FL 33101</li>
            </ul>
        </div>
        
        <div class="last-updated">
            <i class="fas fa-clock"></i>
            Last Updated: January 1, 2024 | Effective Date: January 1, 2024
            <i class="fas fa-check-circle"></i>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>