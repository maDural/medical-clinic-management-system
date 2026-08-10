<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessibility - Florida Medical Clinic</title>
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
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .accessibility-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .accessibility-hero::before {
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
        
        .accessibility-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }
        
        .accessibility-hero p {
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
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Main Content */
        .accessibility-content {
            background: white;
            padding: 60px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            margin: 20px auto 60px;
            position: relative;
            z-index: 2;
        }
        
        /* Section Styles */
        .accessibility-section {
            margin-bottom: 45px;
        }
        
        .accessibility-section:last-child {
            margin-bottom: 0;
        }
        
        .accessibility-section h2 {
            color: #1a1a2e;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }
        
        .accessibility-section h2 i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .accessibility-section h3 {
            color: #667eea;
            font-size: 20px;
            font-weight: 600;
            margin: 25px 0 15px;
        }
        
        .accessibility-section h3 i {
            margin-right: 8px;
        }
        
        .accessibility-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 15px;
        }
        
        .accessibility-section ul {
            margin: 15px 0 15px 25px;
            color: #555;
            line-height: 1.8;
        }
        
        .accessibility-section li {
            margin-bottom: 10px;
            font-size: 15px;
        }
        
        .accessibility-section li strong {
            color: #2c3e50;
        }
        
        /* Commitment Box */
        .commitment-box {
            background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
            padding: 30px;
            border-radius: 20px;
            margin: 30px 0;
            text-align: center;
            border-left: 4px solid #667eea;
            position: relative;
            overflow: hidden;
        }
        
        .commitment-box::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 20px;
            font-size: 120px;
            color: rgba(102, 126, 234, 0.1);
            font-family: Georgia, serif;
        }
        
        .commitment-box p {
            font-size: 18px;
            font-weight: 500;
            color: #2c3e50;
            position: relative;
            z-index: 1;
        }
        
        /* Feature Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .feature-item {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-3px);
            background: #f0f2ff;
        }
        
        .feature-item i {
            font-size: 28px;
            color: #667eea;
            margin-bottom: 12px;
        }
        
        .feature-item h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        
        .feature-item p {
            font-size: 13px;
            margin-bottom: 0;
        }
        
        /* Feedback Form */
        .feedback-form {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 35px;
            border-radius: 20px;
            margin-top: 25px;
        }
        
        .feedback-form h3 {
            color: #1a1a2e;
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .feedback-form input,
        .feedback-form select,
        .feedback-form textarea {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        .feedback-form input:focus,
        .feedback-form select:focus,
        .feedback-form textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .feedback-form button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .feedback-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Contact Info Cards */
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .contact-card {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            background: #f0f2ff;
        }
        
        .contact-card i {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 12px;
        }
        
        .contact-card strong {
            display: block;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        
        .contact-card p {
            margin-bottom: 0;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .accessibility-hero h1 {
                font-size: 32px;
            }
            
            .accessibility-hero p {
                font-size: 16px;
            }
            
            .accessibility-content {
                padding: 30px 25px;
                margin-top: -30px;
            }
            
            .accessibility-section h2 {
                font-size: 22px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .feedback-form {
                padding: 25px;
            }
            
            .commitment-box p {
                font-size: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .accessibility-hero h1 {
                font-size: 28px;
            }
            
            .contact-cards {
                grid-template-columns: 1fr;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="accessibility-hero">
    <h1><i class="fas fa-universal-access"></i> Accessibility Statement</h1>
    <p>We are committed to providing accessible healthcare for everyone</p>
</div>

<div class="container">
    <div class="accessibility-content">
        <!-- Commitment Box -->
        <div class="commitment-box">
            <p><strong>Our Commitment:</strong> Florida Medical Clinic is dedicated to ensuring that our website and services are accessible to all individuals, including those with disabilities. We believe that everyone deserves equal access to quality healthcare.</p>
        </div>
        
        <!-- Accessibility Features -->
        <div class="accessibility-section">
            <h2><i class="fas fa-laptop"></i> Accessibility Features</h2>
            <p>Our website includes the following accessibility features to ensure a seamless experience for all users:</p>
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-universal-access"></i>
                    <h4>Screen Reader Compatible</h4>
                    <p>Works with JAWS, NVDA, and VoiceOver</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-keyboard"></i>
                    <h4>Keyboard Navigation</h4>
                    <p>Full functionality with Tab, Enter, Space keys</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-text-height"></i>
                    <h4>Text Resizing</h4>
                    <p>Use Ctrl+ / Ctrl- to adjust text size</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-adjust"></i>
                    <h4>High Contrast</h4>
                    <p>Clear color contrast for readability</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-image"></i>
                    <h4>Alt Text</h4>
                    <p>Descriptive text for all images</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-heading"></i>
                    <h4>Clear Headings</h4>
                    <p>Proper structure for easy navigation</p>
                </div>
            </div>
        </div>
        
        <!-- Physical Accessibility -->
        <div class="accessibility-section">
            <h2><i class="fas fa-building"></i> Physical Accessibility</h2>
            <p>Our clinic facilities are designed with accessibility in mind to ensure all patients feel welcome and comfortable:</p>
            <ul>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Wheelchair-accessible</strong> entrances and exam rooms</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Accessible parking spaces</strong> near building entrances</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Automatic door openers</strong> at main entrances</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Elevators</strong> to all floors</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Accessible restrooms</strong> on every floor</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Sign language interpreters</strong> available upon request</li>
                <li><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> <strong>Reading assistance</strong> for visually impaired patients</li>
            </ul>
        </div>
        
        <!-- Assistive Technologies -->
        <div class="accessibility-section">
            <h2><i class="fas fa-microphone-alt"></i> Assistive Technologies</h2>
            <p>We support various assistive technologies to accommodate different needs:</p>
            <ul>
                <li><strong>Screen magnifiers</strong> (ZoomText, Windows Magnifier)</li>
                <li><strong>Speech recognition software</strong> (Dragon NaturallySpeaking)</li>
                <li><strong>Screen readers</strong> (JAWS, NVDA, VoiceOver, TalkBack)</li>
                <li><strong>Alternative input devices</strong> (trackballs, joysticks, touch screens)</li>
            </ul>
        </div>
        
        <!-- WCAG Compliance -->
        <div class="accessibility-section">
            <h2><i class="fas fa-globe"></i> Web Content Accessibility Guidelines (WCAG)</h2>
            <p>We strive to comply with WCAG 2.1 Level AA standards, which include four key principles:</p>
            <ul>
                <li><strong>Perceivable:</strong> Information and UI must be presentable to users in ways they can perceive</li>
                <li><strong>Operable:</strong> UI components and navigation must be operable</li>
                <li><strong>Understandable:</strong> Information and UI operation must be understandable</li>
                <li><strong>Robust:</strong> Content must be robust enough to be interpreted by various user agents</li>
            </ul>
        </div>
        
        <!-- Mobile Accessibility -->
        <div class="accessibility-section">
            <h2><i class="fas fa-mobile-alt"></i> Mobile Accessibility</h2>
            <p>Our mobile-friendly website ensures accessibility on smartphones and tablets:</p>
            <ul>
                <li><strong>Responsive design</strong> that adapts to screen size</li>
                <li><strong>Touch-optimized buttons</strong> and controls</li>
                <li><strong>Support for mobile screen readers</strong> (VoiceOver, TalkBack)</li>
                <li><strong>Sufficient touch target sizes</strong> for easy interaction</li>
            </ul>
        </div>
        
        <!-- PDF and Documents -->
        <div class="accessibility-section">
            <h2><i class="fas fa-file-pdf"></i> PDF and Document Accessibility</h2>
            <p>We strive to provide all downloadable documents in accessible formats. If you need an alternative format, please contact us for assistance.</p>
        </div>
        
        <!-- Ongoing Efforts -->
        <div class="accessibility-section">
            <h2><i class="fas fa-chart-line"></i> Ongoing Efforts</h2>
            <p>We are continuously working to improve our accessibility through:</p>
            <ul>
                <li><strong>Regular accessibility audits</strong> and testing</li>
                <li><strong>Staff training</strong> on accessibility best practices</li>
                <li><strong>Updating content</strong> to meet accessibility standards</li>
                <li><strong>Incorporating user feedback</strong> for continuous improvement</li>
            </ul>
        </div>
        
        <!-- Third-Party Content -->
        <div class="accessibility-section">
            <h2><i class="fas fa-external-link-alt"></i> Third-Party Content</h2>
            <p>While we strive to ensure accessibility, some third-party content integrated into our site may not be fully accessible. We work closely with our partners to improve their accessibility standards.</p>
        </div>
        
        <!-- Feedback and Assistance -->
        <div class="accessibility-section">
            <h2><i class="fas fa-comment-dots"></i> Feedback and Assistance</h2>
            <p>We welcome your feedback on the accessibility of our website and services. If you experience any difficulty accessing any part of our site or need assistance, please contact us:</p>
            
            <div class="feedback-form">
                <h3><i class="fas fa-paper-plane"></i> Request Assistance or Provide Feedback</h3>
                <form method="POST" action="">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-row">
                        <input type="tel" name="phone" placeholder="Phone Number">
                        <select name="assistance_type" required>
                            <option value="">Select Type</option>
                            <option value="accessibility_issue">Accessibility Issue</option>
                            <option value="assistance_request">Request Assistance</option>
                            <option value="feedback">Accessibility Feedback</option>
                        </select>
                    </div>
                    <textarea name="message" rows="4" placeholder="Please describe the issue or your request..." required></textarea>
                    <button type="submit" name="submit_feedback"><i class="fas fa-paper-plane"></i> Submit Request</button>
                </form>
            </div>
            
            <p style="margin: 25px 0 15px;">You can also reach our Accessibility Coordinator directly:</p>
            <div class="contact-cards">
                <div class="contact-card">
                    <i class="fas fa-phone-alt"></i>
                    <strong>Phone</strong>
                    <p>+27 72 202 1793<br>TTY: 911 for relay service</p>
                </div>
                <div class="contact-card">
                    <i class="fas fa-envelope"></i>
                    <strong>Email</strong>
                    <p>mthethelelin7@<br>gmail.com</p>
                </div>
                <div class="contact-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Address</strong>
                    <p>R33 Dundee Main Road<br>Tugela Ferry, 3010</p>
                </div>
            </div>
        </div>
        
        <!-- Closing Commitment -->
        <div class="commitment-box" style="margin-bottom: 0;">
            <p><i class="fas fa-heart" style="color: #667eea;"></i> We are committed to providing equal access to healthcare for all individuals. Your feedback helps us improve.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>