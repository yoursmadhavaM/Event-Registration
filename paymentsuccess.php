<?php
require 'db_config.php';
session_start();

$showSuccess = false;
$fullname = '';
$email = '';
$participant_id = '';
$transaction_id = '';

// Check if user has a valid session
if (isset($_SESSION['participant_id'])) {
    $participant_id = $_SESSION['participant_id'];
    
    $stmt = $conn->prepare("SELECT fullname, email, payment_status, transaction_id FROM participants WHERE id=?");
    $stmt->bind_param("i", $participant_id);
    $stmt->execute();
    $stmt->bind_result($fullname, $email, $payment_status, $transaction_id);
    if ($stmt->fetch() && $payment_status === 'successful') {
        $showSuccess = true;
    }
    $stmt->close();
    
    // Clear session data after successful verification
    if ($showSuccess) {
        unset($_SESSION['participant_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_fullname']);
    }
}

// Also check if payment was successful via GET parameter (fallback)
if (!$showSuccess && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    
    $stmt = $conn->prepare("SELECT id, fullname, email, payment_status FROM participants WHERE transaction_id=? AND payment_status='successful'");
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $stmt->bind_result($participant_id, $fullname, $email, $payment_status);
    if ($stmt->fetch()) {
        $showSuccess = true;
        $transaction_id = $payment_id;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Successful - YAICESS Innovation Conference 2K25</title>
  <style>
    body { 
        font-family: 'Segoe UI', sans-serif; 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
        margin: 0; 
    }
    .container { 
        background: #fff; 
        padding: 40px; 
        border-radius: 15px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        max-width: 500px; 
        width: 100%; 
        text-align: center; 
    }
    h1 { color: #0d47a1; margin-bottom: 20px; }
    .success-icon {
        font-size: 60px;
        color: #4caf50;
        margin-bottom: 20px;
    }
    .event-details { 
        margin: 20px 0; 
        color: #333; 
        text-align: left;
    }
    .event-details p {
        margin: 8px 0;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .event-details p:last-child {
        border-bottom: none;
    }
    .calendar-btn { 
        display: inline-block; 
        margin-top: 20px; 
        padding: 12px 24px; 
        background: #0d47a1; 
        color: #fff; 
        border: none; 
        border-radius: 6px; 
        font-size: 16px; 
        text-decoration: none; 
        cursor: pointer; 
        transition: background 0.3s;
    }
    .calendar-btn:hover { background: #08306b; }
    .error { 
        color: #b71c1c; 
        font-size: 18px; 
        background: #ffebee;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ffcdd2;
    }
    .registration-id { 
        background: #e3f2fd; 
        padding: 15px; 
        border-radius: 8px; 
        margin: 20px 0; 
        border-left: 4px solid #2196f3;
    }
    .transaction-info {
        background: #f1f8e9;
        padding: 15px;
        border-radius: 8px;
        margin: 15px 0;
        border-left: 4px solid #4caf50;
    }
    .home-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background: #667eea;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.3s;
    }
    .home-btn:hover {
        background: #5a6fd8;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php if ($showSuccess): ?>
      <div class="success-icon">✓</div>
      <h1>Payment Successful!</h1>
      <div class="event-details">
        <p><strong>Event:</strong> YAICESS Innovation Conference 2K25</p>
        <p><strong>Name:</strong> <?= htmlspecialchars($fullname) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Date:</strong> July 30, 2025</p>
        <p><strong>Location:</strong> Hyderabad, India</p>
        <p><strong>Contact:</strong> info@techconf2025.com</p>
      </div>
      
      <div class="registration-id">
        <strong>Registration ID:</strong> <?= $participant_id ?>
      </div>
      
      <?php if ($transaction_id): ?>
      <div class="transaction-info">
        <strong>Transaction ID:</strong> <?= htmlspecialchars($transaction_id) ?>
      </div>
      <?php endif; ?>
      
      <a href="data:text/calendar;charset=utf8,BEGIN:VCALENDAR%0AVERSION:2.0%0ABEGIN:VEVENT%0ASUMMARY:YAICESS%20Innovation%20Conference%202K25%0ADTSTART:20250730T043000Z%0ADTEND:20250730T120000Z%0ALOCATION:Hyderabad%2C%20India%0ADESCRIPTION:Join%20us%20for%20the%20YAICESS%20Innovation%20Conference%202K25!%0AEND:VEVENT%0AEND:VCALENDAR" download="YAICESS-Conference.ics" class="calendar-btn">📅 Add to Calendar</a>
      
      <p style="margin-top:30px; color:#0d47a1; font-weight: bold;">We look forward to seeing you at the event!</p>
      
      <a href="userform.html" class="home-btn">← Back to Home</a>
    <?php else: ?>
      <div class="error">
        <strong>Payment Verification Failed</strong><br><br>
        Payment not found or not successful.<br>
        Please contact support if you believe this is an error.
      </div>
      <p style="margin-top:20px;"><a href="userform.html" style="color:#0d47a1;">← Back to Registration</a></p>
    <?php endif; ?>
  </div>
</body>
</html>
