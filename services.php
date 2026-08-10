<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Florida Medical Clinic</title>
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
        .services-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .services-hero::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .services-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .services-hero p {
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
            max-width: 1200px;
            margin: 40px auto 0;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 36px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 15px;
        }

        .section-header p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            font-size: 16px;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .service-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .service-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .service-content {
            padding: 28px;
        }

        .service-content h3 {
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .service-content p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .service-link:hover {
            color: #764ba2;
            gap: 12px;
        }

        /* Service Detail Modal */
        .service-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .service-modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 28px;
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 40px;
            animation: modalFadeIn 0.3s ease;
            position: relative;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: #333;
            transform: rotate(90deg);
        }

        .modal-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 15px;
        }

        .modal-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .modal-description {
            color: #555;
            line-height: 1.8;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .modal-section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 25px 0 15px;
        }

        .modal-list {
            list-style: none;
            padding: 0;
        }

        .modal-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            font-size: 14px;
        }

        .modal-list li::before {
            content: '✓';
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
        }

        /* Video Section - TikTok Style */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .video-card {
            background: #1a1a2e;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 100%;
            height: 0;
            overflow: hidden;
            background: #000;
        }

        .video-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-duration {
            position: absolute;
            bottom: 60px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .video-title {
            padding: 12px 15px;
            font-weight: 600;
            color: #fff;
            font-size: 13px;
            background: #1a1a2e;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .video-title i {
            margin-right: 6px;
            color: #ff0050;
        }

        /* ===================== */
        /* MEDICAL DEPARTMENTS - REDESIGNED */
        /* ===================== */
        .department-section {
            background: white;
            padding: 60px 50px;
            border-radius: 24px;
            margin-bottom: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .department-section h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .department-section .section-subtitle {
            text-align: center;
            color: #667eea;
            margin-bottom: 45px;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .department-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .department-item {
            background: #f8f9fa;
            padding: 30px 25px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .department-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: all 0.4s ease;
            border-radius: 20px;
        }

        .department-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .department-item:hover::before {
            opacity: 1;
        }

        .department-item:hover .department-icon,
        .department-item:hover h3,
        .department-item:hover p {
            position: relative;
            z-index: 1;
            color: white;
        }

        .department-item:hover .department-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .department-icon {
            font-size: 48px;
            margin-bottom: 18px;
            display: inline-block;
            transition: all 0.4s ease;
            background: rgba(102, 126, 234, 0.1);
            padding: 15px;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 50px;
        }

        .department-item:hover .department-icon {
            background: rgba(255,255,255,0.2);
        }

        .department-item h3 {
            color: #1a1a2e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .department-item p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        .department-item .learn-more-dept {
            display: inline-block;
            margin-top: 15px;
            color: #667eea;
            font-weight: 600;
            font-size: 13px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
        }

        .department-item:hover .learn-more-dept {
            opacity: 1;
            transform: translateY(0);
            color: white;
        }

        .department-item .learn-more-dept i {
            transition: all 0.3s ease;
        }

        .department-item .learn-more-dept:hover i {
            transform: translateX(5px);
        }

        /* Why Choose Us Section */
        .why-choose-us {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px;
            border-radius: 24px;
            margin-bottom: 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .why-choose-us::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        }

        .why-choose-us h2 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .why-choose-us .section-subtitle {
            color: rgba(255,255,255,0.9);
            margin-bottom: 50px;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 35px;
            position: relative;
            z-index: 1;
        }

        .feature {
            text-align: center;
            padding: 20px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.1);
        }

        .feature-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .feature h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 60px 20px;
            border-radius: 24px;
            margin-bottom: 50px;
        }

        .cta-section h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta-section p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            gap: 15px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .services-hero h1 { font-size: 42px; }
            .section-header h2 { font-size: 32px; }
            .department-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .services-hero h1 { font-size: 32px; }
            .services-hero p { font-size: 16px; }
            .department-section {
                padding: 30px 20px;
            }
            .department-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .why-choose-us { padding: 40px 25px; }
            .why-choose-us h2 { font-size: 28px; }
            .cta-section h2 { font-size: 24px; }
            .services-grid { gap: 20px; }
            .modal-content { padding: 25px; }
            .modal-title { font-size: 24px; }
            .video-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .features-grid { grid-template-columns: 1fr; }
            .video-grid { grid-template-columns: 1fr; }
            .department-item { padding: 20px; }
            .department-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
                padding: 12px;
                line-height: 36px;
            }
        }
    </style>
</head>
<body>

<!-- Services Hero Section -->
<div class="services-hero">
    <h1>Our Medical Services</h1>
    <p>Comprehensive healthcare services tailored to meet your needs</p>
</div>

<div class="container">
    <!-- Featured Services -->
    <div class="featured-section">
        <div class="section-header">
            <h2>Featured Services</h2>
            <p>Advanced medical care with a personal touch</p>
        </div>
        <div class="services-grid">
            <!-- Primary Care -->
            <div class="service-card">
                <img src="images/primary-care.jpg" alt="Primary Care" class="service-image">
                <div class="service-content">
                    <h3>Primary Care</h3>
                    <p>Comprehensive primary care services for patients of all ages. Regular check-ups, preventive care, and management of chronic conditions.</p>
                    <a href="#" class="service-link" onclick="openModal('primary-care'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Cardiology -->
            <div class="service-card">
                <img src="images/cardiology.jpg" alt="Cardiology" class="service-image">
                <div class="service-content">
                    <h3>Cardiology</h3>
                    <p>Expert heart care including diagnostics, treatment, and preventive cardiology. Advanced cardiac imaging and stress testing available.</p>
                    <a href="#" class="service-link" onclick="openModal('cardiology'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Neurology -->
            <div class="service-card">
                <img src="images/neurology.jpg" alt="Neurology" class="service-image">
                <div class="service-content">
                    <h3>Neurology</h3>
                    <p>Specialized care for nervous system disorders. Treatment for migraines, epilepsy, Parkinson's, and other neurological conditions.</p>
                    <a href="#" class="service-link" onclick="openModal('neurology'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Dental Care -->
            <div class="service-card">
                <img src="images/dental-care.jpg" alt="Dental Care" class="service-image">
                <div class="service-content">
                    <h3>Dental Care</h3>
                    <p>Complete dental services including cleanings, fillings, crowns, and cosmetic dentistry. Family-friendly dental care.</p>
                    <a href="#" class="service-link" onclick="openModal('dental'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Pediatrics -->
            <div class="service-card">
                <img src="images/pediatrics.jpg" alt="Pediatrics" class="service-image">
                <div class="service-content">
                    <h3>Pediatrics</h3>
                    <p>Compassionate care for children from birth through adolescence. Well-child visits, vaccinations, and developmental screenings.</p>
                    <a href="#" class="service-link" onclick="openModal('pediatrics'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <!-- Women's Health -->
            <div class="service-card">
                <img src="images/womens-health.jpg" alt="Women's Health" class="service-image">
                <div class="service-content">
                    <h3>Women's Health</h3>
                    <p>Comprehensive women's health services including obstetrics, gynecology, mammography, and reproductive health.</p>
                    <a href="#" class="service-link" onclick="openModal('womens-health'); return false;">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Departments - Redesigned -->
    <div class="department-section">
        <h2>Our Medical Departments</h2>
        <div class="section-subtitle">Specialized Care Across Multiple Disciplines</div>
        <div class="department-grid">
            <div class="department-item">
                <div class="department-icon">🩺</div>
                <h3>Internal Medicine</h3>
                <p>Diagnosis and treatment of adult diseases with comprehensive, patient-centered care.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">🦴</div>
                <h3>Orthopedics</h3>
                <p>Specialized care for bones, joints, and muscles with advanced treatment options.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">👁️</div>
                <h3>Ophthalmology</h3>
                <p>Comprehensive eye care and vision services with state-of-the-art technology.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">🫁</div>
                <h3>Pulmonology</h3>
                <p>Expert care for lung and respiratory conditions with advanced diagnostic tools.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">🩸</div>
                <h3>Endocrinology</h3>
                <p>Specialized treatment for hormone and metabolic disorders with personalized care.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">🧪</div>
                <h3>Laboratory</h3>
                <p>Comprehensive diagnostic testing and accurate results with quick turnaround times.</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="why-choose-us">
        <h2>Why Choose Florida Medical Clinic?</h2>
        <div class="section-subtitle">Excellence in every aspect of care</div>
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">⭐</div>
                <h3>Experienced Doctors</h3>
                <p>Board-certified physicians with years of experience</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🏆</div>
                <h3>Modern Technology</h3>
                <p>State-of-the-art medical equipment and facilities</p>
            </div>
            <div class="feature">
                <div class="feature-icon">💚</div>
                <h3>Patient-Centered Care</h3>
                <p>Personalized treatment plans for every patient</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🕒</div>
                <h3>Flexible Hours</h3>
                <p>Extended hours and weekend appointments available</p>
            </div>
        </div>
    </div>

    <!-- Insurance & Payment -->
    <div class="department-section">
        <h2>Insurance & Payment Options</h2>
        <div class="section-subtitle">Making healthcare accessible and affordable</div>
        <div class="department-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            <div class="department-item">
                <div class="department-icon">📋</div>
                <h3>Insurance Accepted</h3>
                <p>We accept most major insurance plans including Medicare and Medicaid</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">💳</div>
                <h3>Flexible Payment</h3>
                <p>Payment plans and financial assistance available</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">🏦</div>
                <h3>Self-Pay Options</h3>
                <p>Affordable rates for uninsured patients</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="department-item">
                <div class="department-icon">📱</div>
                <h3>Online Payments</h3>
                <p>Easy and secure online bill payment system</p>
                <span class="learn-more-dept">Learn More <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Ready to Schedule an Appointment?</h2>
        <p>Book your visit today and experience quality healthcare</p>
        <a href="register.php" class="cta-button">
            Create Account Now <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<!-- Service Modal -->
<div class="service-modal" id="serviceModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">✕</button>
        <h2 class="modal-title" id="modalTitle">Service Name</h2>
        <img class="modal-image" id="modalImage" src="" alt="">
        <div class="modal-description" id="modalDescription"></div>

        <h3 class="modal-section-title">What to Expect</h3>
        <ul class="modal-list" id="modalList"></ul>

        <h3 class="modal-section-title">Educational Videos</h3>
        <div class="video-grid" id="videoGrid"></div>
    </div>
</div>

<script>
    // Service Data with Local Videos
    const services = {
        'primary-care': {
            title: 'Primary Care',
            image: 'images/primary-care.jpg',
            description: 'Primary care is your first point of contact for healthcare. Our primary care physicians provide comprehensive, continuous care for patients of all ages. We focus on prevention, early detection, and management of chronic conditions.',
            whatToExpect: [
                'Comprehensive health assessments and physical exams',
                'Preventive care and wellness screenings',
                'Management of chronic conditions like diabetes and hypertension',
                'Immunizations and vaccinations',
                'Referrals to specialists when needed',
                'Health education and lifestyle guidance'
            ],
            videos: [
                { file: 'videos/primary-care-1.mp4', title: 'Understanding Primary Care', duration: '2:24' },
                { file: 'videos/primary-care-2.mp4', title: 'Preventive Health Screenings', duration: '00:42' },
                { file: 'videos/primary-care-3.mp4', title: 'Managing Chronic Conditions', duration: '00:43' }
            ]
        },
        'cardiology': {
            title: 'Cardiology',
            image: 'images/cardiology.jpg',
            description: 'Our cardiology department provides expert care for all heart-related conditions. From prevention to advanced treatment, we are committed to keeping your heart healthy.',
            whatToExpect: [
                'Advanced cardiac diagnostics and testing',
                'Stress testing and cardiac imaging',
                'Treatment for heart disease and conditions',
                'Hypertension management',
                'Post-cardiac event rehabilitation',
                'Preventive cardiology services'
            ],
            videos: [
                { file: 'videos/cardiology-1.mp4', title: 'Heart Health Basics', duration: '2:25' },
                { file: 'videos/cardiology-2.mp4', title: 'Understanding Cardiac Testing', duration: '00:52' },
                { file: 'videos/cardiology-3.mp4', title: 'Preventing Heart Disease', duration: '1:02' }
            ]
        },
        'neurology': {
            title: 'Neurology',
            image: 'images/neurology.jpg',
            description: 'Our neurology team specializes in diagnosing and treating disorders of the nervous system, including the brain, spinal cord, and nerves.',
            whatToExpect: [
                'Comprehensive neurological examinations',
                'Treatment for migraines and headaches',
                'Management of epilepsy and seizure disorders',
                'Care for Parkinson\'s disease and movement disorders',
                'Multiple sclerosis treatment',
                'Stroke prevention and recovery care'
            ],
            videos: [
                { file: 'videos/neurology-1.mp4', title: 'Understanding Neurological Conditions', duration: '00:41' },
                { file: 'videos/neurology-2.mp4', title: 'Migraine Management', duration: '1:41' },
                { file: 'videos/neurology-3.mp4', title: 'Living with Parkinson\'s', duration: '1:34' }
            ]
        },
        'dental': {
            title: 'Dental Care',
            image: 'images/dental-care.jpg',
            description: 'Our dental care services provide comprehensive oral health care for the entire family. From routine cleanings to cosmetic procedures, we ensure healthy smiles.',
            whatToExpect: [
                'Routine dental cleanings and check-ups',
                'Fillings, crowns, and bridges',
                'Cosmetic dentistry services',
                'Root canal therapy',
                'Emergency dental care',
                'Children\'s dental services'
            ],
            videos: [
                { file: 'videos/dental-1.mp4', title: 'Oral Health Basics', duration: '2:13' },
                { file: 'videos/dental-2.mp4', title: 'Children\'s Dental Care', duration: '0:08' },
                { file: 'videos/dental-3.mp4', title: 'Cosmetic Dentistry Overview', duration: '0:07' }
            ]
        },
        'pediatrics': {
            title: 'Pediatrics',
            image: 'images/pediatrics.jpg',
            description: 'Our pediatric team provides compassionate, specialized care for children from birth through adolescence. We focus on healthy growth and development.',
            whatToExpect: [
                'Well-child visits and developmental screenings',
                'Vaccinations and immunizations',
                'Management of childhood illnesses and conditions',
                'Nutrition and growth guidance',
                'Behavioral and mental health support',
                'Adolescent healthcare services'
            ],
            videos: [
                { file: 'videos/pediatrics-1.mp4', title: 'Child Development Milestones', duration: '00:36' },
                { file: 'videos/pediatrics-2.mp4', title: 'Vaccination Information', duration: '00:24' },
                { file: 'videos/pediatrics-3.mp4', title: 'Nutrition for Growing Children', duration: '2:30' }
            ]
        },
        'womens-health': {
            title: 'Women\'s Health',
            image: 'images/womens-health.jpg',
            description: 'Our women\'s health services provide comprehensive, compassionate care for women at all stages of life. From adolescence through menopause and beyond.',
            whatToExpect: [
                'Obstetric care and prenatal services',
                'Gynecological exams and screenings',
                'Mammography and breast health services',
                'Reproductive health and family planning',
                'Hormone management',
                'Menopause care and support'
            ],
            videos: [
                { file: 'videos/womens-health-1.mp4', title: 'Women\'s Health Overview', duration: '00:20' },
                { file: 'videos/womens-health-2.mp4', title: 'Breast Health Awareness', duration: '1:05' },
                { file: 'videos/womens-health-3.mp4', title: 'Prenatal Care Guide', duration: '1:39' }
            ]
        }
    };

    function openModal(serviceId) {
        const service = services[serviceId];
        if (!service) return;

        document.getElementById('modalTitle').textContent = service.title;
        document.getElementById('modalImage').src = service.image;
        document.getElementById('modalDescription').textContent = service.description;

        const list = document.getElementById('modalList');
        list.innerHTML = '';
        service.whatToExpect.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            list.appendChild(li);
        });

        const videoGrid = document.getElementById('videoGrid');
        videoGrid.innerHTML = '';
        service.videos.forEach(video => {
            const card = document.createElement('div');
            card.className = 'video-card';
            card.innerHTML = `
                <div class="video-wrapper">
                    <video controls playsinline>
                        <source src="${video.file}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-duration">${video.duration || '0:00'}</div>
                </div>
                <div class="video-title">
                    <i class="fas fa-play-circle"></i> ${video.title}
                </div>
            `;
            videoGrid.appendChild(card);
        });

        document.getElementById('serviceModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('serviceModal').classList.remove('active');
        document.body.style.overflow = '';
        document.querySelectorAll('.video-wrapper video').forEach(video => {
            video.pause();
        });
    }

    document.getElementById('serviceModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>