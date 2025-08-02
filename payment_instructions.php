<?php
require 'db_config.php';

// Get user details from session or URL parameter
$user_id = $_GET['user_id'] ?? null;
$user_data = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM participants WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Instructions - YAICESS Conference</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        h1 { text-align: center; color: #0d47a1; margin-bottom: 30px; }
        .payment-info { background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { display: inline-block; padding: 15px 30px; background: #0d47a1; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; margin: 10px 5px; }
        .btn:hover { background: #08306b; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Instructions</h1>
        
        <?php if ($user_data): ?>
            <div class="payment-info">
                <h3>Registration Details:</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($user_data['fullname']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email']) ?></p>
                <p><strong>Amount:</strong> ₹3000</p>
                <p><strong>Event:</strong> YAICESS Innovation Conference 2K25</p>
            </div>
        <?php endif; ?>
        
        <div class="warning">
            <strong>Important:</strong> Please complete your payment to confirm your registration.
        </div>
        
        <h3>Payment Options:</h3>
        <p>Click the button below to proceed to payment. You will be redirected to a secure Razorpay payment page.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="#" id="paymentBtn" class="btn">Proceed to Payment</a>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4>Payment Instructions:</h4>
            <ul>
                <li>Click "Proceed to Payment" button</li>
                <li>Complete payment on the Razorpay page</li>
                <li>Save your transaction ID for reference</li>
                <li>Update your payment status after completion</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="project.html" style="color: #0d47a1; text-decoration: none;">← Back to Home</a>
        </div>
    </div>

    <script>
        document.getElementById('paymentBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Generate unique parameters for fresh payment page
            const timestamp = Date.now();
            const random = Math.floor(Math.random() * 9000) + 1000;
            
            // Determine which payment link to use based on registration count
            const isOdd = <?= $user_data ? ($user_data['id'] % 2 == 1 ? 'true' : 'false') : 'true' ?>;
            
            let paymentUrl;
            if (isOdd) {
                paymentUrl = `https://rzp.io/rzp/TwT7RZb?ts=${timestamp}&r=${random}`;
            } else {
                paymentUrl = `https://rzp.io/rzp/dlvt2m6s?ts=${timestamp}&r=${random}`;
            }
            
            // Open payment in new tab
            window.open(paymentUrl, '_blank');
            
            // Show instructions
            alert('Payment page opened in new tab. Please complete your payment and then update your payment status.');
        });
    </script>
</body>
</html> 