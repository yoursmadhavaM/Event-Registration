<?php
// Debug version of register.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Registration Debug</h2>";

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p>✅ POST request received</p>";
    
    // Check if all required fields are present
    $required_fields = ['fullname', 'email', 'phone', 'username', 'password'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        echo "<p style='color: red;'>❌ Missing fields: " . implode(', ', $missing_fields) . "</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    } else {
        echo "<p>✅ All required fields present</p>";
    }
    
    // Test database connection
    require 'db_config.php';
    
    if ($conn->connect_error) {
        echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    } else {
        echo "<p>✅ Database connection successful</p>";
    }
    
    // Sanitize inputs
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = md5($_POST['password']);
    $referral = htmlspecialchars(trim($_POST['referral'] ?? ''));
    
    echo "<p>✅ Input sanitization completed</p>";
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>❌ Invalid email format: $email</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    } else {
        echo "<p>✅ Email validation passed</p>";
    }
    
    // Check if email already exists
    $check_sql = "SELECT id, payment_status FROM participants WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    
    if (!$check_stmt) {
        echo "<p style='color: red;'>❌ Prepare statement failed: " . $conn->error . "</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    }
    
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->bind_result($existing_id, $existing_payment_status);
    $check_stmt->fetch();
    $check_stmt->close();
    
    if ($existing_id) {
        if ($existing_payment_status === 'successful') {
            echo "<p style='color: red;'>❌ Email already has successful payment</p>";
        } else if ($existing_payment_status === 'pending') {
            echo "<p style='color: red;'>❌ Email has pending payment</p>";
        }
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    } else {
        echo "<p>✅ Email is unique</p>";
    }
    
    // Insert new registration
    $insert_sql = "INSERT INTO participants (fullname, email, phone, username, password, referral, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $insert_stmt = $conn->prepare($insert_sql);
    
    if (!$insert_stmt) {
        echo "<p style='color: red;'>❌ Insert prepare statement failed: " . $conn->error . "</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
        exit();
    }
    
    $insert_stmt->bind_param("ssssss", $fullname, $email, $phone, $username, $password, $referral);
    
    if ($insert_stmt->execute()) {
        $participant_id = $conn->insert_id;
        echo "<p style='color: green;'>✅ Registration successful! Participant ID: $participant_id</p>";
        
        // Start session and store data
        session_start();
        $_SESSION['participant_id'] = $participant_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_fullname'] = $fullname;
        
        echo "<p>✅ Session data stored</p>";
        echo "<p><a href='payment_session.php'>→ Continue to Payment</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Insert failed: " . $insert_stmt->error . "</p>";
        echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
    }
    
    $insert_stmt->close();
    $conn->close();
    
} else {
    echo "<p style='color: orange;'>⚠️ This page should be accessed via POST request from the registration form</p>";
    echo "<p><a href='userform.html'>← Go to Registration Form</a></p>";
}
?> 