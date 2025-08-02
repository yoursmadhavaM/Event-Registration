<?php
require_once 'send_email.php';

// Test email functionality
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (!empty($testEmail)) {
        $result = testEmailFunctionality($testEmail);
        
        if ($result) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>✓ Test Email Sent Successfully!</h3>
                <p>Test email has been sent to: <strong>$testEmail</strong></p>
                <p>Please check your inbox (and spam folder) for the registration confirmation email.</p>
            </div>";
        } else {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>✗ Test Email Failed!</h3>
                <p>Failed to send test email to: <strong>$testEmail</strong></p>
                <p>Please check your email configuration in <code>email_config.php</code> and ensure your server supports email sending.</p>
            </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Functionality</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="email"]:focus {
            border-color: #667eea;
            outline: none;
        }
        button {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #5a6fd8;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        code {
            background: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Email Functionality</h1>
        
        <div class="info">
            <h3>📧 Email Configuration</h3>
            <p>This test will send a registration confirmation email to verify that the email functionality is working correctly.</p>
            <p><strong>Event:</strong> YAICESS Innovation Conference 2K25</p>
            <p><strong>Date:</strong> July 30, 2025</p>
        </div>
        
        <div class="warning">
            <h3>⚠️ Important Notes</h3>
            <ul>
                <li>Make sure your server supports email sending (PHP mail function)</li>
                <li>Check your email configuration in <code>email_config.php</code></li>
                <li>For production use, consider using PHPMailer for better reliability</li>
                <li>Test emails might go to spam folder - check there too</li>
            </ul>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="test_email">Test Email Address:</label>
                <input type="email" id="test_email" name="test_email" placeholder="Enter your email address" required>
            </div>
            
            <button type="submit">Send Test Email</button>
        </form>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h3>📋 Email Features Tested:</h3>
            <ul>
                <li>✓ HTML email template with event details</li>
                <li>✓ Registration confirmation message</li>
                <li>✓ Event agenda and important information</li>
                <li>✓ Professional styling and branding</li>
                <li>✓ Error logging and tracking</li>
            </ul>
        </div>
    </div>
</body>
</html> 