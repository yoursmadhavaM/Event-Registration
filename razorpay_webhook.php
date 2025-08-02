<?php
require 'db_config.php';

// Enable error logging
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'razorpay_webhook.log');

// Get the webhook payload
$input = @file_get_contents("php://input");
$event_json = json_decode($input, true);

// Log the webhook event
error_log("Webhook received: " . json_encode($event_json));

// For security, verify the webhook signature here (see Razorpay docs)
// You should add signature verification for production

if ($event_json['event'] == 'payment.captured') {
    $payment_id = $event_json['payload']['payment']['entity']['id'];
    $amount = $event_json['payload']['payment']['entity']['amount'];
    $email = $event_json['payload']['payment']['entity']['email'] ?? '';
    $contact = $event_json['payload']['payment']['entity']['contact'] ?? '';
    $status = $event_json['payload']['payment']['entity']['status'];
    
    error_log("Payment captured - ID: $payment_id, Email: $email, Status: $status, Amount: $amount");
    
    // Only update if payment is successful
    if ($status == 'captured') {
        $updated = false;
        
        // Try to find user by email first (most reliable)
        if (!empty($email)) {
            // Check if this email already has a successful payment
            $checkStmt = $conn->prepare("SELECT id, payment_status FROM participants WHERE email = ? AND payment_status = 'successful'");
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $checkStmt->bind_result($existing_id, $existing_status);
            $checkStmt->fetch();
            $checkStmt->close();
            
            if ($existing_id) {
                error_log("Email $email already has a successful payment. Ignoring duplicate payment.");
            } else {
                // Update only pending payments for this email
                $stmt = $conn->prepare("UPDATE participants SET payment_status='successful', transaction_id=? WHERE email=? AND payment_status='pending' LIMIT 1");
                $stmt->bind_param("ss", $payment_id, $email);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    error_log("Payment status updated successfully for email: $email with transaction ID: $payment_id");
                    $updated = true;
                } else {
                    error_log("No pending payment found for email: $email");
                }
                $stmt->close();
            }
        }
        
        // If email not found, try by phone number
        if (!$updated && !empty($contact)) {
            // Check if this phone already has a successful payment
            $checkStmt = $conn->prepare("SELECT id, payment_status FROM participants WHERE phone = ? AND payment_status = 'successful'");
            $checkStmt->bind_param("s", $contact);
            $checkStmt->execute();
            $checkStmt->bind_result($existing_id, $existing_status);
            $checkStmt->fetch();
            $checkStmt->close();
            
            if ($existing_id) {
                error_log("Phone $contact already has a successful payment. Ignoring duplicate payment.");
            } else {
                $stmt = $conn->prepare("UPDATE participants SET payment_status='successful', transaction_id=? WHERE phone=? AND payment_status='pending' LIMIT 1");
                $stmt->bind_param("ss", $payment_id, $contact);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    error_log("Payment status updated successfully for phone: $contact with transaction ID: $payment_id");
                    $updated = true;
                } else {
                    error_log("No pending payment found for phone: $contact");
                }
                $stmt->close();
            }
        }
        
        // If still not found, try to find any pending payment (fallback)
        if (!$updated) {
            $stmt = $conn->prepare("UPDATE participants SET payment_status='successful', transaction_id=? WHERE payment_status='pending' ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $payment_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                error_log("Payment status updated for most recent pending payment with transaction ID: $payment_id");
                $updated = true;
            } else {
                error_log("No pending payments found in database");
            }
            $stmt->close();
        }
        
        if (!$updated) {
            error_log("Could not find user to update payment status for payment ID: $payment_id");
        }
    } else {
        error_log("Payment not captured, status: $status");
    }
}

// Always acknowledge the webhook
http_response_code(200);
echo "Webhook processed";
?> 