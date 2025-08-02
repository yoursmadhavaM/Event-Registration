<?php
require 'db_config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $transaction_id = $_POST['transaction_id'];
    
    // Debug: Check if we received the data
    error_log("Received email: " . $email);
    error_log("Received transaction_id: " . $transaction_id);
    
    // Verify if user exists and update payment status
    $stmt = $conn->prepare("SELECT id, payment_status FROM participants WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        error_log("User found with ID: " . $user['id'] . ", Current status: " . $user['payment_status']);
        
        if ($user['payment_status'] == 'pending') {
            // Update payment status
            $update_stmt = $conn->prepare("UPDATE participants SET payment_status='successful', transaction_id=? WHERE email=? AND payment_status='pending'");
            $update_stmt->bind_param("ss", $transaction_id, $email);
            
            if ($update_stmt->execute()) {
                $message = "Payment status updated successfully! Your registration is now complete.";
                error_log("Payment status updated successfully for email: " . $email);
            } else {
                $error = "Error updating payment status: " . $update_stmt->error;
                error_log("Error updating payment status: " . $update_stmt->error);
            }
            $update_stmt->close();
        } else {
            $error = "Payment status is already: " . $user['payment_status'];
            error_log("Payment already processed for email: " . $email);
        }
    } else {
        $error = "Email not found in database.";
        error_log("Email not found: " . $email);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Payment Status - YAICESS Conference</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        h1 { text-align: center; color: #0d47a1; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background: #0d47a1; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #08306b; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #0d47a1; text-decoration: none; }
        .debug { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Payment Status</h1>
        
        <div class="debug">
            <strong>Debug Info:</strong><br>
            - Make sure you have the transaction_id column in your database<br>
            - Check that your email matches exactly what you registered with<br>
            - Transaction ID should be from your Razorpay payment receipt
        </div>
        
        <?php if ($message): ?>
            <div class="message success"><?= $message ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your registered email">
            </div>
            
            <div class="form-group">
                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" required placeholder="Enter your payment transaction ID">
            </div>
            
            <button type="submit" class="btn">Update Payment Status</button>
        </form>
        
        <div class="back-link">
            <a href="project.html">← Back to Home</a>
        </div>
    </div>
</body>
</html> 