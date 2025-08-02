<?php
require_once 'db_config.php';
require_once 'razorpay_config.php';
require_once 'send_email.php';

// Only allow in test mode
if (!isTestMode()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Test mode not enabled']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$participant_id = $input['participant_id'] ?? null;

if (!$participant_id) {
    echo json_encode(['success' => false, 'message' => 'Participant ID required']);
    exit();
}

try {
    // Update payment status to successful
    $stmt = $conn->prepare("UPDATE participants SET payment_status = 'successful', transaction_id = ? WHERE id = ?");
    $transaction_id = 'TEST_' . time() . '_' . $participant_id;
    $stmt->bind_param("si", $transaction_id, $participant_id);
    
    if ($stmt->execute()) {
        // Get user details for email
        $userStmt = $conn->prepare("SELECT fullname, email FROM participants WHERE id = ?");
        $userStmt->bind_param("i", $participant_id);
        $userStmt->execute();
        $userStmt->bind_result($fullname, $email);
        $userStmt->fetch();
        $userStmt->close();
        
        // Send payment confirmation email
        if ($fullname && $email) {
            sendPaymentConfirmationEmail($fullname, $email, $participant_id, $transaction_id);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Test payment completed successfully',
            'transaction_id' => $transaction_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();

/**
 * Send payment confirmation email
 */
function sendPaymentConfirmationEmail($fullname, $email, $participant_id, $transaction_id) {
    $subject = "Payment Confirmation - YAICESS Innovation Conference 2K25";
    
    $htmlBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Payment Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .success-icon { font-size: 48px; margin-bottom: 20px; }
            .payment-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4caf50; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='success-icon'>✓</div>
                <h1>Payment Successful!</h1>
                <p>Your registration is now complete</p>
            </div>
            
            <div class='content'>
                <h2>Dear " . htmlspecialchars($fullname) . ",</h2>
                
                <p>Thank you for completing your payment for <strong>YAICESS Innovation Conference 2K25</strong>!</p>
                
                <div class='payment-details'>
                    <h3>Payment Details:</h3>
                    <p><strong>Registration ID:</strong> " . $participant_id . "</p>
                    <p><strong>Transaction ID:</strong> " . $transaction_id . "</p>
                    <p><strong>Amount:</strong> ₹1.00</p>
                    <p><strong>Status:</strong> <span style='color: #4caf50; font-weight: bold;'>PAID</span></p>
                </div>
                
                <div class='payment-details'>
                    <h3>Event Details:</h3>
                    <p><strong>Event:</strong> YAICESS Innovation Conference 2K25</p>
                    <p><strong>Date:</strong> July 30, 2025</p>
                    <p><strong>Time:</strong> 10:00 AM - 6:00 PM</p>
                    <p><strong>Location:</strong> Hyderabad, India</p>
                </div>
                
                <h3>What's Next?</h3>
                <ul>
                    <li>You will receive event updates via email</li>
                    <li>Please arrive 30 minutes before the event starts</li>
                    <li>Bring a valid ID for verification</li>
                    <li>Check your email for any additional instructions</li>
                </ul>
                
                <p>We look forward to seeing you at the event!</p>
                
                <p>Best regards,<br>
                <strong>YAICESS Team</strong></p>
            </div>
            
            <div class='footer'>
                <p>This is an automated email. Please do not reply to this message.</p>
                <p>For support, contact: info@techconf2025.com</p>
            </div>
        </div>
    </body>
    </html>";
    
    $textBody = "
    Payment Confirmation - YAICESS Innovation Conference 2K25
    
    Dear " . $fullname . ",
    
    Thank you for completing your payment for YAICESS Innovation Conference 2K25!
    
    Payment Details:
    - Registration ID: " . $participant_id . "
    - Transaction ID: " . $transaction_id . "
    - Amount: ₹1.00
    - Status: PAID
    
    Event Details:
    - Event: YAICESS Innovation Conference 2K25
    - Date: July 30, 2025
    - Time: 10:00 AM - 6:00 PM
    - Location: Hyderabad, India
    
    What's Next?
    - You will receive event updates via email
    - Please arrive 30 minutes before the event starts
    - Bring a valid ID for verification
    - Check your email for any additional instructions
    
    We look forward to seeing you at the event!
    
    Best regards,
    YAICESS Team
    
    For support, contact: info@techconf2025.com";
    
    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: YAICESS Innovation Conference <noreply@yaicess.com>',
        'Reply-To: info@techconf2025.com',
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Send email
    mail($email, $subject, $htmlBody, implode("\r\n", $headers));
}
?> 