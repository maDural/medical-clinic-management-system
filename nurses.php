<?php include 'includes/header.php'; ?>

<div class="page-hero">
    <div class="container">
        <h1>For Professional Nurses</h1>
        <p>Empowering black women nurses within their communities</p>
    </div>
</div>

<div class="container">
    <div class="page-content">
        <div class="section-header">
            <span class="section-subtitle">Empowerment Through Healthcare</span>
            <h2>Transform Healthcare, Transform Lives</h2>
        </div>
        
        <div class="benefits-grid">
            <div class="benefit-item">
                <div class="benefit-icon">👩‍⚕️</div>
                <h3>Become Entrepreneurs</h3>
                <p>With a solid support structure to help you succeed in your own healthcare business.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">🏥</div>
                <h3>Transform Healthcare</h3>
                <p>Lead the effort in transforming the healthcare system from the ground up.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">📚</div>
                <h3>Develop Skills</h3>
                <p>Ongoing coaching and mentoring to develop management and operational skills.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">💼</div>
                <h3>Own Your Business</h3>
                <p>Ultimately own your own clinic and become a leader in your community.</p>
            </div>
        </div>
        
        <div class="innovation-section">
            <h2>The Innovation Behind Our Approach</h2>
            <p>Our approach is based on shifting primary healthcare tasks to Professional Nurses and leveraging a highly developed distribution network to ensure more people have access to quality healthcare.</p>
        </div>
        
        <div class="application-process">
            <h2>Application Process</h2>
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Information Session</h4>
                        <p>Interested Professional Nurses attend an information session to learn about the opportunity.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Initial Inquiry</h4>
                        <p>Complete an initial inquiry form and submit for evaluation.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Application</h4>
                        <p>If minimum criteria are met, complete a detailed application form.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="criteria-section">
            <h3>Minimum Selection Criteria</h3>
            <ul>
                <li><i class="fas fa-check-circle"></i> South African B-BBBEE Citizen (African, Coloured or Indian)</li>
                <li><i class="fas fa-check-circle"></i> Black Female Professional Nurse</li>
                <li><i class="fas fa-check-circle"></i> Minimum 5 years' experience in Primary Healthcare</li>
                <li><i class="fas fa-check-circle"></i> Dispensing Course certified</li>
                <li><i class="fas fa-check-circle"></i> Nimart or Basic HIV Management certified</li>
                <li><i class="fas fa-check-circle"></i> Post Graduate Diploma in Primary Healthcare</li>
            </ul>
        </div>
        
        <!-- Apply Box -->
        <div class="apply-box">
            <div class="apply-icon">📄</div>
            <h3>How to Apply</h3>
            <p>Please click on the link below to complete the Florida Medical Clinic Preliminary Questionnaire.</p>
            <p class="apply-note"><i class="fas fa-info-circle"></i> You will be required to upload a copy of Primary Healthcare Diploma, so please use a laptop / computer to complete the form.</p>
            <a href="apply.php" class="apply-btn">
                <i class="fas fa-file-alt"></i> Apply Now
            </a>
        </div>
        
        <!-- Status Check Section - NEW -->
        <div class="status-check-section">
            <div class="status-check-icon">🔍</div>
            <h3>Already Applied?</h3>
            <p>Check the status of your application and view admin responses</p>
            <a href="application_status.php" class="status-btn">
                <i class="fas fa-eye"></i> Check Application Status
            </a>
        </div>
        
        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</div>

<style>
    .page-hero {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
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
    
    .innovation-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 50px;
        text-align: center;
    }
    
    .innovation-section h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 15px;
    }
    
    .innovation-section p {
        color: #666;
        font-size: 16px;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .application-process {
        margin-bottom: 50px;
    }
    
    .application-process h2 {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 40px;
    }
    
    .process-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    
    .step {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        background: #f8f9fa;
        padding: 25px;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    
    .step:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .step-content h4 {
        color: #1a1a2e;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .step-content p {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .criteria-section {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 16px;
        border-left: 4px solid #f093fb;
        margin-bottom: 50px;
    }
    
    .criteria-section h3 {
        color: #1a1a2e;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .criteria-section ul {
        list-style: none;
        padding: 0;
    }
    
    .criteria-section ul li {
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #555;
        font-size: 14px;
    }
    
    .criteria-section ul li i {
        color: #28a745;
    }
    
    .apply-box {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 50px;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .apply-icon {
        font-size: 60px;
        margin-bottom: 20px;
        display: block;
    }
    
    .apply-box h3 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .apply-box p {
        font-size: 16px;
        line-height: 1.7;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .apply-note {
        font-size: 14px !important;
        opacity: 0.8 !important;
        margin-top: 15px !important;
    }
    
    .apply-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 40px;
        background: white;
        color: #f093fb;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 25px;
    }
    
    .apply-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    /* Status Check Section - NEW */
    .status-check-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 50px;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .status-check-icon {
        font-size: 60px;
        margin-bottom: 20px;
        display: block;
    }
    
    .status-check-section h3 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .status-check-section p {
        font-size: 16px;
        line-height: 1.7;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .status-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 40px;
        background: white;
        color: #667eea;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 25px;
    }
    
    .status-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        background: #f8f9fa;
    }
    
    .back-link {
        text-align: center;
        margin-top: 20px;
    }
    
    .back-link a {
        color: #f093fb;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .back-link a:hover {
        gap: 12px;
    }
    
    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 32px;
        }
        .benefits-grid {
            grid-template-columns: 1fr;
        }
        .process-steps {
            grid-template-columns: 1fr;
        }
        .step {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .apply-box {
            padding: 30px 20px;
        }
        .status-check-section {
            padding: 30px 20px;
        }
        .apply-btn, .status-btn {
            padding: 12px 25px;
            font-size: 14px;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>