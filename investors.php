<?php include 'includes/header.php'; ?>

<div class="page-hero">
    <div class="container">
        <h1>For Investors</h1>
        <p>Your funding contribution can create more access, more business opportunities, and more meaningful jobs.</p>
    </div>
</div>

<div class="container">
    <div class="page-content">
        <div class="section-header">
            <span class="section-subtitle">Invest in Healthcare Transformation</span>
            <h2>Create Impact Through Investment</h2>
        </div>
        
        <div class="benefits-grid">
            <div class="benefit-item">
                <div class="benefit-icon">🌱</div>
                <h3>Sustainable Future</h3>
                <p>Investing in a sustainable future for healthcare in South Africa.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">📊</div>
                <h3>Tax &amp; Legal Benefits</h3>
                <p>Tax and legal benefits for your investment contributions.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">⭐</div>
                <h3>BBBEE Recognition</h3>
                <p>BBBEE recognition for your organization through meaningful investment.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">🌍</div>
                <h3>Sustainable Development</h3>
                <p>Helping to achieve our Sustainable Development Goals.</p>
            </div>
        </div>
        
        <div class="investment-impact">
            <h2>Your Impact</h2>
            <div class="impact-grid">
                <div class="impact-card">
                    <div class="impact-number">100+</div>
                    <p>Clinics Supported</p>
                </div>
                <div class="impact-card">
                    <div class="impact-number">50k+</div>
                    <p>Patients Served Annually</p>
                </div>
                <div class="impact-card">
                    <div class="impact-number">200+</div>
                    <p>Jobs Created</p>
                </div>
                <div class="impact-card">
                    <div class="impact-number">15+</div>
                    <p>Communities Reached</p>
                </div>
            </div>
        </div>
        
        <div class="investment-opportunities">
            <h2>Investment Opportunities</h2>
            <div class="opportunity-grid">
                <div class="opportunity-card">
                    <div class="opportunity-icon">🏥</div>
                    <h3>Clinic Expansion</h3>
                    <p>Fund the establishment of new clinics in underserved communities.</p>
                </div>
                <div class="opportunity-card">
                    <div class="opportunity-icon">📱</div>
                    <h3>Technology Innovation</h3>
                    <p>Support digital health solutions and telemedicine infrastructure.</p>
                </div>
                <div class="opportunity-card">
                    <div class="opportunity-icon">👩‍⚕️</div>
                    <h3>Nurse Training</h3>
                    <p>Invest in professional development and training programs.</p>
                </div>
                <div class="opportunity-card">
                    <div class="opportunity-icon">🧪</div>
                    <h3>Research &amp; Development</h3>
                    <p>Fund healthcare research and innovative treatment methods.</p>
                </div>
            </div>
        </div>
        
        <div class="contact-investors">
            <h2>Ready to Invest?</h2>
            <p>Contact our investment team to discuss how you can make a difference.</p>
            <a href="contact.php" class="contact-btn">
                <i class="fas fa-envelope"></i> Get in Touch
            </a>
        </div>
        
        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</div>

<style>
    .page-hero {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: #1a1a2e;
        text-align: center;
        padding: 80px 20px;
        margin-top: -20px;
    }
    
    .page-hero h1 {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 15px;
    }
    
    .page-hero p {
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.95;
    }
    
    .page-content {
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .benefit-item {
        text-align: center;
        padding: 30px 20px;
        background: #f8f9fa;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    
    .benefit-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    
    .benefit-icon {
        font-size: 50px;
        margin-bottom: 15px;
        display: block;
    }
    
    .benefit-item h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 10px;
    }
    
    .benefit-item p {
        color: #666;
        line-height: 1.6;
    }
    
    .investment-impact {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 50px;
    }
    
    .investment-impact h2 {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 30px;
    }
    
    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
    }
    
    .impact-card {
        text-align: center;
        background: white;
        padding: 25px;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    
    .impact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    
    .impact-number {
        font-size: 40px;
        font-weight: 800;
        color: #43e97b;
        margin-bottom: 5px;
    }
    
    .impact-card p {
        color: #666;
        font-size: 14px;
    }
    
    .investment-opportunities {
        margin-bottom: 50px;
    }
    
    .investment-opportunities h2 {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 30px;
    }
    
    .opportunity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }
    
    .opportunity-card {
        background: #f8f9fa;
        padding: 30px 25px;
        border-radius: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .opportunity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    
    .opportunity-icon {
        font-size: 45px;
        margin-bottom: 15px;
        display: block;
    }
    
    .opportunity-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 10px;
    }
    
    .opportunity-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .contact-investors {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        padding: 50px;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 40px;
        color: #1a1a2e;
    }
    
    .contact-investors h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .contact-investors p {
        font-size: 16px;
        margin-bottom: 25px;
        opacity: 0.9;
    }
    
    .contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 40px;
        background: white;
        color: #43e97b;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .contact-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .back-link {
        text-align: center;
        margin-top: 20px;
    }
    
    .back-link a {
        color: #43e97b;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .back-link a:hover {
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 32px;
        }
        .benefits-grid {
            grid-template-columns: 1fr;
        }
        .impact-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .opportunity-grid {
            grid-template-columns: 1fr;
        }
        .contact-investors {
            padding: 30px 20px;
        }
    }
    
    @media (max-width: 480px) {
        .impact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>