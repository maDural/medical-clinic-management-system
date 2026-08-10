<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Information - Florida Medical Clinic</title>
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
        .insurance-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .insurance-hero::before {
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

        .insurance-hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.8s ease;
        }

        .insurance-hero p {
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

        /* Insurance Grid */
        .insurance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .insurance-card {
            background: white;
            padding: 35px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
        }

        .insurance-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .insurance-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }

        .insurance-card h2 {
            color: #1a1a2e;
            margin-bottom: 15px;
            font-size: 24px;
            font-weight: 700;
        }

        .insurance-card p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .insurance-list {
            list-style: none;
            padding: 0;
        }

        .insurance-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .insurance-list li:hover {
            transform: translateX(5px);
            color: #667eea;
        }

        .insurance-list li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 12px;
            font-size: 16px;
        }

        /* Accepted Plans Section */
        .accepted-plans {
            background: white;
            padding: 50px;
            border-radius: 24px;
            margin-bottom: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .accepted-plans h2 {
            text-align: center;
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .accepted-plans .section-subtitle {
            text-align: center;
            color: #667eea;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 25px;
        }

        .plan-item {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .plan-item:hover {
            transform: translateY(-5px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .plan-item:hover h3,
        .plan-item:hover p {
            color: white;
        }

        .plan-item h3 {
            color: #667eea;
            margin-bottom: 8px;
            font-size: 18px;
            font-weight: 700;
        }

        .plan-item p {
            color: #888;
            font-size: 12px;
        }

        /* FAQ Section */
        .faq-section {
            background: white;
            padding: 50px;
            border-radius: 24px;
            margin-bottom: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .faq-section h2 {
            color: #1a1a2e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }

        .faq-section .section-subtitle {
            text-align: center;
            color: #667eea;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .faq-item {
            margin-bottom: 20px;
            border: 1px solid #eef2f6;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            border-color: #667eea;
        }

        .faq-item h3 {
            padding: 18px 22px;
            color: #1a1a2e;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .faq-item h3:hover {
            background: #f0f2ff;
        }

        .faq-item h3 i {
            color: #667eea;
            transition: transform 0.3s ease;
        }

        .faq-item.active h3 i {
            transform: rotate(180deg);
        }

        .faq-item p {
            padding: 0 22px 20px 22px;
            color: #666;
            line-height: 1.7;
            display: none;
            font-size: 14px;
        }

        .faq-item.active p {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* CTA Box */
        .cta-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 60px;
            border-radius: 24px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .cta-box h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .cta-box p {
            font-size: 16px;
            margin-bottom: 25px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        .cta-btn {
            display: inline-block;
            padding: 14px 35px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .insurance-hero h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .insurance-hero h1 {
                font-size: 32px;
            }

            .insurance-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .accepted-plans, .faq-section {
                padding: 30px;
            }

            .plans-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .cta-box {
                padding: 40px 25px;
            }

            .cta-box h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .plans-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="insurance-hero">
    <h1>Insurance Information</h1>
    <p>Understanding your coverage and payment options</p>
</div>

<div class="container">
    <!-- Main Insurance Cards -->
    <div class="insurance-grid">
        <div class="insurance-card">
            <div class="insurance-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Accepted Insurance Plans</h2>
            <p>We accept most major insurance plans to ensure you have access to quality healthcare.</p>
            <ul class="insurance-list">
                <li>Medicare & Medicaid</li>
                <li>Blue Cross Blue Shield</li>
                <li>Aetna</li>
                <li>Cigna</li>
                <li>United Healthcare</li>
                <li>Humana</li>
                <li>Tricare</li>
                <li>WellCare</li>
                <li>Molina Healthcare</li>
            </ul>
        </div>
        
        <div class="insurance-card">
            <div class="insurance-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h2>Payment Options</h2>
            <p>We offer flexible payment options for your convenience.</p>
            <ul class="insurance-list">
                <li>Cash Payments</li>
                <li>Credit/Debit Cards (Visa, MasterCard, Amex)</li>
                <li>Personal Checks</li>
                <li>Health Savings Account (HSA)</li>
                <li>Flexible Spending Account (FSA)</li>
                <li>CareCredit Medical Financing</li>
                <li>Payment Plans Available</li>
            </ul>
        </div>
    </div>
    
    <!-- Accepted Plans -->
    <div class="accepted-plans">
        <h2>📍 Insurance Plans We Accept</h2>
        <div class="section-subtitle">Partnering with leading insurance providers</div>
        <div class="plans-grid">
            <div class="plan-item"><h3>Medicare</h3><p>Part A, B, C, D</p></div>
            <div class="plan-item"><h3>Medicaid</h3><p>All Plans</p></div>
            <div class="plan-item"><h3>Blue Cross</h3><p>PPO, HMO, POS</p></div>
            <div class="plan-item"><h3>Aetna</h3><p>All Plans</p></div>
            <div class="plan-item"><h3>Cigna</h3><p>All Plans</p></div>
            <div class="plan-item"><h3>United HC</h3><p>All Plans</p></div>
            <div class="plan-item"><h3>Humana</h3><p>All Plans</p></div>
            <div class="plan-item"><h3>Tricare</h3><p>All Plans</p></div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="section-subtitle">Common questions about insurance and billing</div>
        
        <div class="faq-item">
            <h3>
                Do you accept my insurance plan?
                <i class="fas fa-chevron-down"></i>
            </h3>
            <p>We accept most major insurance plans. Please call our billing department at (555) 123-4567 to verify your specific plan coverage before your appointment.</p>
        </div>
        
        <div class="faq-item">
            <h3>
                What if I don't have insurance?
                <i class="fas fa-chevron-down"></i>
            </h3>
            <p>We offer self-pay discounts and flexible payment plans for patients without insurance. Please contact our billing department to discuss your options.</p>
        </div>
        
        <div class="faq-item">
            <h3>
                Do I need a referral to see a specialist?
                <i class="fas fa-chevron-down"></i>
            </h3>
            <p>This depends on your insurance plan. HMO plans typically require a referral from your primary care physician. PPO plans generally do not. Please check with your insurance provider.</p>
        </div>
        
        <div class="faq-item">
            <h3>
                What is my copay or coinsurance?
                <i class="fas fa-chevron-down"></i>
            </h3>
            <p>Copay and coinsurance amounts vary by insurance plan. Please contact your insurance provider or our billing department for specific information about your coverage.</p>
        </div>
    </div>
    
    <!-- CTA Box -->
    <div class="cta-box">
        <h2>Have Questions About Billing?</h2>
        <p>Our billing specialists are here to help you understand your coverage</p>
        <a href="contact.php" class="cta-btn">
            <i class="fas fa-headset"></i> Contact Billing Department
        </a>
    </div>
</div>

<script>
    document.querySelectorAll('.faq-item h3').forEach(item => {
        item.addEventListener('click', () => {
            const parent = item.parentElement;
            parent.classList.toggle('active');
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>