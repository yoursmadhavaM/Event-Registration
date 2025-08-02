<!DOCTYPE html>
<html>
<head>
    <title>Payment Success Guide - YAICESS Conference</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        h1 { text-align: center; color: #0d47a1; margin-bottom: 30px; }
        .success-box { background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #c3e6cb; }
        .info-box { background: #e3f2fd; color: #0d47a1; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #bbdefb; }
        .step { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #0d47a1; }
        .btn { display: inline-block; padding: 12px 24px; background: #0d47a1; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; margin: 10px 5px; }
        .btn:hover { background: #08306b; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Payment Successful!</h1>
        
        <div class="success-box">
            <h3>✅ Your payment has been completed successfully!</h3>
            <p>Thank you for registering for the YAICESS Innovation Conference 2K25. Your payment has been processed and your registration is confirmed.</p>
        </div>
        
        <div class="info-box">
            <h3>📋 Next Steps:</h3>
            <p>To complete your registration process, please update your payment status in our system:</p>
        </div>
        
        <div class="step">
            <h4>Step 1: Find Your Transaction ID</h4>
            <p>Look for your transaction ID in the payment confirmation email or SMS from Razorpay. It usually looks like: <strong>pay_xxxxxxxxxxxxx</strong></p>
        </div>
        
        <div class="step">
            <h4>Step 2: Update Payment Status</h4>
            <p>Click the button below to update your payment status:</p>
            <a href="payment_status_update.php" class="btn">Update Payment Status</a>
        </div>
        
        <div class="step">
            <h4>Step 3: Enter Your Details</h4>
            <p>On the update page, enter:</p>
            <ul>
                <li>Your registered email address</li>
                <li>Your transaction ID from Razorpay</li>
            </ul>
        </div>
        
        <div class="warning">
            <h4>⚠️ Important Notes:</h4>
            <ul>
                <li>Make sure to use the exact email address you registered with</li>
                <li>Transaction ID is case-sensitive</li>
                <li>If you don't have the transaction ID, contact support</li>
                <li>Your registration is not complete until payment status is updated</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="project.html" class="btn">← Back to Home</a>
            <a href="payment_status_update.php" class="btn">Update Payment Status</a>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4>📞 Need Help?</h4>
            <p>If you have any issues updating your payment status, please contact us:</p>
            <p><strong>Email:</strong> info@techconf2025.com</p>
            <p><strong>Phone:</strong> [Your contact number]</p>
        </div>
    </div>
</body>
</html> 