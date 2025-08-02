<?php
session_start();
require_once 'razorpay_config.php';

// Check if user is coming from registration
if (!isset($_SESSION['participant_id'])) {
    header("Location: userform.html");
    exit();
}

// Check if Razorpay is properly configured
if (!isRazorpayConfigured()) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; margin: 20px; text-align: center;'>
        <h3>⚠️ Payment Configuration Error</h3>
        <p>Razorpay payment gateway is not properly configured. Please contact the administrator.</p>
        <p><a href='userform.html' style='color: #721c24;'>← Back to Registration</a></p>
    </div>";
    exit();
}

$participant_id = $_SESSION['participant_id'];
$user_email = $_SESSION['user_email'];
$user_fullname = $_SESSION['user_fullname'];

// Check if this user has already made a successful payment
require 'db_config.php';
$checkStmt = $conn->prepare("SELECT payment_status FROM participants WHERE id = ?");
$checkStmt->bind_param("i", $participant_id);
$checkStmt->execute();
$checkStmt->bind_result($payment_status);
$checkStmt->fetch();
$checkStmt->close();

// If payment is already successful, redirect to success page
if ($payment_status === 'successful') {
    header("Location: paymentsuccess.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Session</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .payment-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            margin: 0;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .user-info h3 {
            margin: 0 0 15px 0;
            color: #333;
        }
        
        .user-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .payment-section {
            margin-top: 30px;
        }
        
        .payment-section h3 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .back-link {
            margin-top: 30px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .status-message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .warning-message {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="header">
            <h1>Complete Your Registration</h1>
            <p>Please complete your payment to finalize your registration</p>
        </div>
        
        <div class="user-info">
            <h3>Registration Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_fullname); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
            <p><strong>Registration ID:</strong> <?php echo $participant_id; ?></p>
        </div>
        
        <div class="warning-message">
            <strong>Important:</strong> Only one payment per email address is allowed. 
            If you have already made a payment with this email, please contact support.
        </div>
        
        <div class="payment-section">
            <h3>Payment</h3>
            
            <?php if (isTestMode()): ?>
                <!-- Test Mode - Skip Payment -->
                <div style="background: #e3f2fd; color: #0d47a1; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2196f3;">
                    <h4>🧪 Test Mode Active</h4>
                    <p><strong>Payment is being skipped for testing purposes.</strong></p>
                    <p>In production, users would complete payment here.</p>
                    <button onclick="completeTestPayment()" style="background: #2196f3; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; margin-top: 10px;">
                        ✅ Complete Registration (Test Mode)
                    </button>
                </div>
            <?php else: ?>
                <!-- Production Mode - Real Payment -->
                <p>Click the button below to proceed with payment:</p>
                
                <!-- Your Razorpay Payment Button -->
                <form>
                    <script src="https://checkout.razorpay.com/v1/payment-button.js" 
                            data-payment_button_id="pl_R08csZoyThJfOj" 
                            async>
                    </script>
                </form>
                
                <div id="payment-error" style="display: none; background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 15px 0;">
                    <strong>Payment Error:</strong> <span id="error-message"></span>
                    <br><br>
                    <button onclick="retryPayment()" style="background: #721c24; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Retry Payment</button>
                </div>
            <?php endif; ?>
        </div>
        
        <div id="status-message" class="status-message"></div>
        
        <div class="back-link">
            <a href="userform.html">← Back to Registration</a>
        </div>
    </div>
    
    <script>
        let paymentCheckInterval;
        let checkCount = 0;
        const maxChecks = 30; // Check for 5 minutes (30 * 10 seconds)
        
        // Function to check payment status
        function checkPaymentStatus() {
            fetch('check_payment_status.php?participant_id=<?php echo $participant_id; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'successful') {
                        clearInterval(paymentCheckInterval);
                        showStatusMessage('Payment successful! Redirecting to success page...', 'success');
                        setTimeout(() => {
                            window.location.href = 'paymentsuccess.php';
                        }, 2000);
                    } else if (data.status === 'pending' && checkCount < maxChecks) {
                        showStatusMessage('Payment in progress... Please wait.', 'pending');
                        checkCount++;
                    } else if (checkCount >= maxChecks) {
                        clearInterval(paymentCheckInterval);
                        showStatusMessage('Payment check timeout. Please contact support if payment was completed.', 'pending');
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
        }
        
        function showStatusMessage(message, type) {
            const statusDiv = document.getElementById('status-message');
            statusDiv.textContent = message;
            statusDiv.className = `status-message status-${type}`;
            statusDiv.style.display = 'block';
        }
        
        // Listen for payment success from Razorpay
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'razorpay_payment_success') {
                showStatusMessage('Payment completed! Verifying payment status...', 'success');
                // Start checking payment status
                setTimeout(() => {
                    checkPaymentStatus();
                    paymentCheckInterval = setInterval(checkPaymentStatus, 10000); // Check every 10 seconds
                }, 3000);
            }
        });
        
        // Listen for payment errors
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'razorpay_payment_error') {
                handlePaymentError(event.data.error);
            }
        });
        
        // Handle Razorpay errors
        function handlePaymentError(error) {
            let errorMessage = 'An error occurred during payment.';
            
            if (error && error.code) {
                switch(error.code) {
                    case 'BAD_REQUEST_ERROR':
                        if (error.description && error.description.includes('Too many requests')) {
                            errorMessage = 'Too many payment requests. Please wait a moment and try again.';
                        } else {
                            errorMessage = 'Invalid payment request. Please try again.';
                        }
                        break;
                    case 'RATE_LIMIT_ERROR':
                        errorMessage = 'Too many requests. Please wait a moment and try again.';
                        break;
                    case 'AUTHENTICATION_ERROR':
                        errorMessage = 'Payment authentication failed. Please contact support.';
                        break;
                    default:
                        errorMessage = error.description || 'Payment failed. Please try again.';
                }
            }
            
            showPaymentError(errorMessage);
        }
        
        function showPaymentError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('payment-error').style.display = 'block';
        }
        
        function retryPayment() {
            document.getElementById('payment-error').style.display = 'none';
            // Reload the page to retry payment
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        // Test mode function to complete registration without payment
        function completeTestPayment() {
            showStatusMessage('Processing test registration...', 'pending');
            
            // Simulate payment completion
            setTimeout(() => {
                // Update payment status in database
                fetch('update_test_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        participant_id: <?php echo $participant_id; ?>,
                        test_mode: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatusMessage('Registration completed successfully! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = 'paymentsuccess.php';
                        }, 2000);
                    } else {
                        showStatusMessage('Error completing registration. Please try again.', 'pending');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatusMessage('Error completing registration. Please try again.', 'pending');
                });
            }, 2000);
        }
        
        // Also start checking after a delay in case the message event doesn't fire
        setTimeout(() => {
            if (!paymentCheckInterval) {
                paymentCheckInterval = setInterval(checkPaymentStatus, 10000);
            }
        }, 30000); // Start checking after 30 seconds
    </script>
</body>
</html> 