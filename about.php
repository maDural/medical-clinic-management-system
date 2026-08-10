<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Florida Medical Clinic</title>
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
        .about-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 150px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 20s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .about-hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }
        
        .about-hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease;
            line-height: 1.2;
        }
        
        /* Container */
        .container {
            max-width: 1300px;
            margin: 0 auto 10px;
            padding: 0 20px;
        }
        
        /* Mission & Vision Cards */
        .mission-vision {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            margin: 10px auto 20px;
            position: relative;
            z-index: 2;
        }
        
        .mv-card {
            background: white;
            padding: 45px 35px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        
        .mv-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 50px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .mv-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 40px;
            color: white;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .mv-card h2 {
            color: #1a1a2e;
            margin-bottom: 15px;
            font-size: 26px;
            font-weight: 700;
        }
        
        .mv-card p {
            color: #666;
            line-height: 1.7;
            font-size: 15px;
        }
        
        /* Story Section */
        .story-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            padding: 80px;
            border-radius: 30px;
            margin-bottom: 60px;
        }
        
        .story-section h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 38px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .section-subtitle {
            text-align: center;
            color: #667eea;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }
        
        .story-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .story-text p {
            color: #444;
            line-height: 1.8;
            margin-bottom: 20px;
            font-size: 15px;
        }
        
        .story-image {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            transition: transform 0.4s ease;
        }
        
        .story-image:hover {
            transform: scale(1.02);
        }
        
        .story-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        /* Values Section */
        .values-section {
            margin-bottom: 60px;
        }
        
        .values-section h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 38px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .value-card {
            text-align: center;
            padding: 35px 25px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
            border: 1px solid #f0f0f0;
        }
        
        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
            border-color: rgba(102, 126, 234, 0.2);
        }
        
        .value-icon {
            font-size: 50px;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .value-card h3 {
            color: #1a1a2e;
            font-size: 20px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        
        .value-card p {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }
        
        /* Team Section */
        .team-section {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            padding: 70px 0;
            border-radius: 30px;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }
        
        .team-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="white" d="M10,50 L90,50 M50,10 L50,90" stroke="white" stroke-width="1"/></svg>');
            background-repeat: repeat;
        }
        
        .team-section h2 {
            text-align: center;
            color: white;
            font-size: 38px;
            margin-bottom: 15px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        
        .team-section .section-subtitle {
            color: rgba(255,255,255,0.9);
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }
        
        .team-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            text-align: center;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .team-photo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .doctor-image {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .team-card h3 {
            margin: 20px 0 5px;
            color: #1a1a2e;
            font-size: 20px;
            font-weight: 700;
        }
        
        .team-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .team-desc {
            color: #666;
            padding: 0 20px 25px;
            line-height: 1.6;
            font-size: 13px;
        }
        
        /* Stats Section */
        .stats-section {
            background: white;
            padding: 60px 0;
            margin-bottom: 60px;
            border-radius: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-top: 8px;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 70px 20px;
            border-radius: 30px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        
        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        
        .cta-section p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 45px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .about-hero h1 {
                font-size: 42px;
            }
            
            .story-content {
                grid-template-columns: 1fr;
            }
            
            .story-section {
                padding: 50px;
            }
        }
        
        @media (max-width: 768px) {
            .about-hero h1 {
                font-size: 32px;
            }
            
            .about-hero p {
                font-size: 16px;
            }
            
            .mission-vision {
                margin-top: -30px;
            }
            
            .mv-card {
                padding: 30px 25px;
            }
            
            .story-section {
                padding: 30px;
            }
            
            .story-section h2,
            .values-section h2,
            .team-section h2 {
                font-size: 28px;
            }
            
            .cta-section h2 {
                font-size: 26px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- About Hero Section -->
<div class="about-hero">
    <h1>About Florida Medical Clinic</h1>
    <p>Providing exceptional healthcare with compassion, innovation, and excellence since 1990</p>
</div>

<div class="container">
    <!-- Mission & Vision -->
    <div class="mission-vision">
        <div class="mv-card">
            <div class="mv-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <h2>Our Mission</h2>
            <p>To provide compassionate, high-quality healthcare services that improve the health and well-being of our community, treating every patient with dignity, respect, and personalized attention.</p>
        </div>
        
        <div class="mv-card">
            <div class="mv-icon">
                <i class="fas fa-eye"></i>
            </div>
            <h2>Our Vision</h2>
            <p>To be the leading healthcare provider in Florida, recognized for clinical excellence, innovative treatments, and exceptional patient experiences.</p>
        </div>
    </div>
    
    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">30+</div>
                <div class="stat-label">Years of Excellence</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Expert Doctors</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100k+</div>
                <div class="stat-label">Happy Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Emergency Care</div>
            </div>
        </div>
    </div>
     
    <!-- Our Story -->
    <div class="story-section">
        <h2>Our Story</h2>
        <div class="section-subtitle">A Legacy of Healing</div>
        <div class="story-content">
            <div class="story-text">
                <p>Founded in 1990, Florida Medical Clinic began with a simple yet powerful vision: to create a healthcare facility where patients receive not just medical treatment, but genuine care and compassion. What started as a small practice with just three doctors has grown into one of Florida's most trusted medical institutions.</p>
                <p>Over the past three decades, we've expanded our services, brought in top medical talent, and invested in cutting-edge technology. Despite our growth, we've never lost sight of what matters most - our patients. Every decision we make, every service we offer, is designed with your health and comfort in mind.</p>
                <p>Today, Florida Medical Clinic serves thousands of patients annually, offering comprehensive medical services across multiple specialties. We're proud to be an integral part of the Florida community, and we look forward to continuing our mission of healing and caring for generations to come.</p>
            </div>
            <div class="story-image">
                <img src="Dash.jpeg" alt="Florida Medical Clinic" class="story-img">
            </div>
        </div>
    </div>
    
    <!-- Our Values -->
    <div class="values-section">
        <h2>Our Core Values</h2>
        <div class="section-subtitle">What Drives Us Every Day</div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">❤️</div>
                <h3>Compassion</h3>
                <p>We treat every patient with empathy, kindness, and understanding, recognizing their unique needs and concerns.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">⭐</div>
                <h3>Excellence</h3>
                <p>We strive for the highest standards in medical care, continuously improving our skills and services.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3>Integrity</h3>
                <p>We are honest, ethical, and transparent in all our interactions with patients, families, and colleagues.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">💡</div>
                <h3>Innovation</h3>
                <p>We embrace new technologies and treatment methods to provide the best possible care.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌍</div>
                <h3>Community</h3>
                <p>We are committed to improving the health and well-being of our local community.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤲</div>
                <h3>Respect</h3>
                <p>We honor the dignity and worth of every person who walks through our doors.</p>
            </div>
        </div>
    </div>
    
    <!-- Our Team -->
    <div class="team-section">
        <h2>Meet Our Leadership Team</h2>
        <div class="section-subtitle">Dedicated Experts Committed to Your Health</div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-photo">
                    <img src="images/doctor1.jpg" alt="Dr. Mthetheleli Ndlovu" class="doctor-image">
                </div>
                <h3>Dr. Mthetheleli Ndlovu</h3>
                <div class="team-title">Chief Medical Officer</div>
                <div class="team-desc">20+ years of experience in internal medicine. Harvard Medical School graduate. Specializes in preventive care and complex diagnoses.</div>
            </div>
            
            <div class="team-card">
                <div class="team-photo">
                    <img src="images/doctor2.jpg" alt="Dr. Nompumelelo Phungula" class="doctor-image">
                </div>
                <h3>Dr. Nompumelelo Phungula</h3>
                <div class="team-title">Head of Pediatrics</div>
                <div class="team-desc">Dedicated to child healthcare with 15 years of pediatric experience. Passionate about childhood wellness and development.</div>
            </div>
            
            <div class="team-card">
                <div class="team-photo">
                    <img src="images/doctor3.png" alt="Dr. Likhona Mgaga" class="doctor-image">
                </div>
                <h3>Dr. Likhona Mgaga</h3>
                <div class="team-title">Chief of Surgery</div>
                <div class="team-desc">Leading surgeon with expertise in minimally invasive procedures. Over 2,000 successful surgeries performed.</div>
            </div>
            
            <!-- NEW: Dr. Banele Hlanguza -->
            <div class="team-card">
                <div class="team-photo">
                    <img src="images/banele.jpeg" alt="Dr. Banele Hlanguza" class="doctor-image">
                </div>
                <h3>Dr. Banele Hlanguza</h3>
                <div class="team-title">Director of Emergency Medicine</div>
                <div class="team-desc">Leading emergency medicine specialist with 12+ years of experience in critical care and trauma management. Committed to providing rapid, life-saving care when you need it most.</div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Ready to Experience Quality Healthcare?</h2>
        <p>Join thousands of satisfied patients who trust us with their health</p>
        <a href="register.php" class="cta-button">Create Account Today <i class="fas fa-arrow-right"></i></a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>