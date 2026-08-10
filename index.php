<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<div class="hero" style="background-image: url('Dash.jpeg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">🏥 Excellence in Healthcare Since 1990</div>
        <h1>Welcome to <span class="highlight">Florida<br> Medical Clinic</span></h1>
        <p>Book your appointment easily online with fast and secure access to quality healthcare.</p>

        <div class="hero-buttons">
            <a href="appointment.php" class="btn primary-btn">
                <span>📅</span> Book Appointment
            </a>
            <a href="services.php" class="btn secondary-btn">
                <span>🩺</span> Our Services
            </a>
        </div>

        <div class="hero-stats">
            <div class="stat">
                <span class="stat-number">20+</span>
                <span class="stat-label">Expert Doctors</span>
            </div>
            <div class="stat">
                <span class="stat-number">10k+</span>
                <span class="stat-label">Happy Patients</span>
            </div>
            <div class="stat">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Emergency Care</span>
            </div>
        </div>
    </div>
</div>

<!-- FEATURES SECTION - Why Choose Us -->
<div class="features-section">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Why Choose Us</span>
            <h2>Providing Quality Healthcare Services</h2>
            <p>We are committed to delivering compassionate, personalized care to every patient.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">👨‍⚕️</div>
                <h3>Expert Doctors</h3>
                <p>Board-certified physicians with years of experience in their specialties.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏥</div>
                <h3>Modern Facilities</h3>
                <p>State-of-the-art equipment and comfortable environment for patients.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💚</div>
                <h3>Compassionate Care</h3>
                <p>Patient-centered approach with genuine care and attention.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Online Booking</h3>
                <p>Easy appointment scheduling from anywhere, anytime.</p>
            </div>
        </div>
    </div>
</div>

<!-- SERVICES PREVIEW SECTION -->
<div class="services-preview">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Our Services</span>
            <h2>Comprehensive Medical Care</h2>
            <p>We offer a wide range of medical services to meet your healthcare needs.</p>
        </div>
        
        <div class="services-grid">
            <div class="service-item">
                <div class="service-icon">🩺</div>
                <h4>Primary Care</h4>
                <p>Family medicine, annual checkups, and preventive care.</p>
            </div>
            <div class="service-item">
                <div class="service-icon">❤️</div>
                <h4>Cardiology</h4>
                <p>Heart health, diagnostics, and treatment.</p>
            </div>
            <div class="service-item">
                <div class="service-icon">👶</div>
                <h4>Pediatrics</h4>
                <p>Comprehensive care for children of all ages.</p>
            </div>
            <div class="service-item">
                <div class="service-icon">👩‍⚕️</div>
                <h4>Women's Health</h4>
                <p>OB/GYN services and reproductive health.</p>
            </div>
            <div class="service-item">
                <div class="service-icon">🧠</div>
                <h4>Neurology</h4>
                <p>Brain, spine, and nervous system care.</p>
            </div>
            <div class="service-item">
                <div class="service-icon">💻</div>
                <h4>Telehealth</h4>
                <p>Virtual consultations from home.</p>
            </div>
        </div>
        
        <div class="text-center">
            <a href="services.php" class="outline-btn">View All Services →</a>
        </div>
    </div>
</div>

<!-- ============================================= -->
<!-- CARDS SECTION - FOR PATIENTS, NURSES, INVESTORS -->
<!-- ============================================= -->
<div class="cards-section">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Our Offerings</span>
            <h2>Comprehensive Healthcare Solutions</h2>
            <p>Explore how we serve patients, empower nurses, and create investment opportunities</p>
        </div>
        
        <div class="cards-grid">
            <!-- Card 1: For Patients -->
            <div class="info-card patients-card">
                <div class="card-header">
                    <div class="card-icon">🩺</div>
                    <h3>For Patients</h3>
                </div>
                <p>Private primary healthcare services that are Convenient, Accessible, Affordable, and High Quality.</p>
                <ul class="card-features">
                    <li>✅ Convenient access to healthcare</li>
                    <li>✅ Affordable quality care</li>
                    <li>✅ Comprehensive medical services</li>
                </ul>
                <a href="patients.php" class="card-btn">Find Out More →</a>
            </div>
            
            <!-- Card 2: For Professional Nurses -->
            <div class="info-card nurses-card">
                <div class="card-header">
                    <div class="card-icon">👩‍⚕️</div>
                    <h3>For Professional Nurses</h3>
                </div>
                <p>Empowering black women nurses to become entrepreneurs, lead healthcare transformation, and own their own businesses.</p>
                <ul class="card-features">
                    <li>✅ Entrepreneur support structure</li>
                    <li>✅ Leadership & coaching</li>
                    <li>✅ Business ownership</li>
                </ul>
                <a href="nurses.php" class="card-btn">Find Out More →</a>
            </div>
            
            <!-- Card 3: For Investors -->
            <div class="info-card investors-card">
                <div class="card-header">
                    <div class="card-icon">💰</div>
                    <h3>For Investors</h3>
                </div>
                <p>Create more access, business opportunities, and meaningful jobs through sustainable healthcare investment.</p>
                <ul class="card-features">
                    <li>✅ Tax & legal benefits</li>
                    <li>✅ BBBEE recognition</li>
                    <li>✅ Sustainable development</li>
                </ul>
                <a href="investors.php" class="card-btn">Find Out More →</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hero Section */
    .hero {
        position: relative;
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 40px;
        animation: fadeInUp 0.8s ease;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        color: white;
        margin-bottom: 20px;
    }
    
    .hero-content h1 {
        font-size: 56px;
        margin-bottom: 20px;
        color: white;
        line-height: 1.2;
    }
    
    .hero-content h1 .highlight {
        background: linear-gradient(135deg, #fff 0%, #a8c0ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-content p {
        font-size: 18px;
        color: rgba(255,255,255,0.9);
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 35px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 16px;
    }
    
    .primary-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .primary-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.6);
    }
    
    .secondary-btn {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    
    .secondary-btn:hover {
        background: white;
        color: #667eea;
        transform: translateY(-3px);
    }
    
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }
    
    .stat {
        text-align: center;
    }
    
    .stat-number {
        display: block;
        font-size: 28px;
        font-weight: bold;
        color: white;
    }
    
    .stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.8);
    }
    
    /* Features Section - Why Choose Us */
    .features-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        position: relative;
    }
    
    .features-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?w=1600&q=80') no-repeat center center fixed;
        background-size: cover;
        opacity: 0.05;
        pointer-events: none;
    }
    
    /* Services Preview Section */
    .services-preview {
        padding: 80px 20px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        position: relative;
    }
    
    .services-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600&q=80') no-repeat center center fixed;
        background-size: cover;
        opacity: 0.03;
        pointer-events: none;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .section-subtitle {
        display: inline-block;
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 14px;
        color: #667eea;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .section-header h2 {
        font-size: 36px;
        color: #1a1a2e;
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .section-header p {
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    
    .feature-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 35px 25px;
        border-radius: 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.15);
        border-color: rgba(102, 126, 234, 0.3);
        background: white;
    }
    
    .feature-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }
    
    .feature-card h3 {
        font-size: 20px;
        color: #1a1a2e;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .feature-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .service-item {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        text-align: center;
        padding: 30px 20px;
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }
    
    .service-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
    }
    
    .service-item:hover h4,
    .service-item:hover p {
        color: white;
    }
    
    .service-icon {
        font-size: 45px;
        margin-bottom: 15px;
    }
    
    .service-item h4 {
        font-size: 18px;
        color: #1a1a2e;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .service-item p {
        font-size: 13px;
        color: #666;
        line-height: 1.6;
    }
    
    .text-center {
        text-align: center;
    }
    
    .outline-btn {
        display: inline-block;
        padding: 12px 30px;
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .outline-btn:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }
    
    /* ============================================= */
    /* CARDS SECTION */
    /* ============================================= */
    .cards-section {
        padding: 80px 20px;
        background: white;
    }
    
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }
    
    .info-card {
        background: white;
        border-radius: 24px;
        padding: 35px 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s ease;
        border: 1px solid #eef2f6;
        display: flex;
        flex-direction: column;
    }
    
    .info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .patients-card:hover {
        border-color: #4facfe;
    }
    
    .nurses-card:hover {
        border-color: #f093fb;
    }
    
    .investors-card:hover {
        border-color: #43e97b;
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .card-icon {
        font-size: 45px;
    }
    
    .card-header h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .info-card p {
        color: #666;
        line-height: 1.7;
        font-size: 15px;
        margin-bottom: 20px;
        flex: 1;
    }
    
    .card-features {
        list-style: none;
        padding: 0;
        margin-bottom: 25px;
    }
    
    .card-features li {
        padding: 8px 0;
        color: #555;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        align-self: flex-start;
        margin-top: auto;
    }
    
    .patients-card .card-btn {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .nurses-card .card-btn {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .investors-card .card-btn {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .card-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        gap: 12px;
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
    
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 36px;
        }
        .hero-buttons {
            gap: 15px;
        }
        .btn {
            padding: 12px 25px;
            font-size: 14px;
        }
        .section-header h2 {
            font-size: 28px;
        }
        .hero-stats {
            gap: 20px;
        }
        .stat-number {
            font-size: 22px;
        }
        .features-grid {
            gap: 20px;
        }
        .services-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .cards-grid {
            grid-template-columns: 1fr;
        }
        .card-header {
            flex-direction: column;
            text-align: center;
        }
        .card-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 28px;
        }
        .hero-buttons {
            flex-direction: column;
            width: 100%;
        }
        .btn {
            width: 100%;
            justify-content: center;
        }
        .services-grid {
            grid-template-columns: 1fr;
        }
        .features-section, .services-preview {
            padding: 50px 20px;
        }
        .cards-section {
            padding: 50px 20px;
        }
        .info-card {
            padding: 25px 20px;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>