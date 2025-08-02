<?php
require 'error_handler.php';
require 'db_config.php';
require 'send_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize all inputs
    $fullname = sanitizeInput($_POST['fullname']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $username = sanitizeInput($_POST['username']);
    $password = md5($_POST['password']);
    $referral = sanitizeInput($_POST['referral']);

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($phone) || empty($username) || empty($_POST['password'])) {
        $errorHandler->logCustomError("Registration failed: Missing required fields", $_POST);
        echo "<script>
            alert('Please fill in all required fields.');
            window.location.href='userform.html';
        </script>";
        exit();
    }

    // Validate email format
    if (!validateEmail($email)) {
        $errorHandler->logCustomError("Registration failed: Invalid email format", ['email' => $email]);
        echo "<script>
            alert('Please enter a valid email address.');
            window.location.href='userform.html';
        </script>";
        exit();
    }

    // Validate database connection
    if (!validateDatabase($conn)) {
        $errorHandler->logCustomError("Registration failed: Database connection error");
        echo "<script>
            alert('System error. Please try again later.');
            window.location.href='userform.html';
        </script>";
        exit();
    }

    // Check if user with this email already exists
    $checkStmt = $conn->prepare("SELECT id, payment_status FROM participants WHERE email = ?");
    if (!$checkStmt) {
        $errorHandler->logCustomError("Registration failed: Database prepare error", $conn->error);
        echo "<script>
            alert('System error. Please try again later.');
            window.location.href='userform.html';
        </script>";
        exit();
    }

    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->bind_result($existing_id, $existing_payment_status);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($existing_id) {
        if ($existing_payment_status === 'successful') {
            $errorHandler->logCustomError("Registration failed: Email already has successful payment", ['email' => $email]);
            echo "<script>
                alert('A registration with this email already exists and payment has been completed. Please use a different email or contact support.');
                window.location.href='userform.html';
            </script>";
            exit();
        } else if ($existing_payment_status === 'pending') {
            $errorHandler->logCustomError("Registration failed: Email has pending payment", ['email' => $email]);
            echo "<script>
                alert('A registration with this email already exists but payment is pending. Please complete your previous payment or contact support.');
                window.location.href='userform.html';
            </script>";
            exit();
        }
    }

    // Insert new registration
    $stmt = $conn->prepare("INSERT INTO participants (fullname, email, phone, username, password, referral, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    if (!$stmt) {
        $errorHandler->logCustomError("Registration failed: Database prepare error for insert", $conn->error);
        echo "<script>
            alert('System error. Please try again later.');
            window.location.href='userform.html';
        </script>";
        exit();
    }

    $stmt->bind_param("ssssss", $fullname, $email, $phone, $username, $password, $referral);

    if ($stmt->execute()) {
        // Get the participant ID for payment tracking
        $participant_id = $conn->insert_id;
        
        // Store participant ID in session for payment tracking
        session_start();
        $_SESSION['participant_id'] = $participant_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_fullname'] = $fullname;
        
        // Send registration confirmation email
        $emailSent = sendRegistrationEmail($fullname, $email, $participant_id);
        
        $errorHandler->logCustomError("Registration successful", [
            'participant_id' => $participant_id,
            'email' => $email,
            'fullname' => $fullname,
            'email_sent' => $emailSent
        ]);
        
        // Redirect to payment page with Razorpay button
        header("Location: payment_session.php");
        exit();
    } else {
        $errorHandler->logCustomError("Registration failed: Database insert error", [
            'error' => $conn->error,
            'data' => ['email' => $email, 'fullname' => $fullname]
        ]);
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href='userform.html';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
