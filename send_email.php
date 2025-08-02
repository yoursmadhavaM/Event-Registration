<?php
require_once 'email_config.php';
require_once 'error_handler.php';

/**
 * Send registration confirmation email
 * @param string $fullname User's full name
 * @param string $email User's email address
 * @param int $participant_id Registration ID
 * @return bool Success status
 */
function sendRegistrationEmail($fullname, $email, $participant_id) {
    global $errorHandler;
    
    try {
        // Get email template
        $emailTemplate = getRegistrationEmailTemplate($fullname, $email, $participant_id);
        
        // Email headers
        $headers = array(
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_EMAIL . '>',
            'Reply-To: ' . EVENT_CONTACT,
            'X-Mailer: PHP/' . phpversion()
        );
        
        // Additional headers for better deliverability
        $headers[] = 'X-Priority: 1';
        $headers[] = 'X-MSMail-Priority: High';
        $headers[] = 'Importance: High';
        
        // Send email using PHP's mail function
        $mailSent = mail(
            $email,
            $emailTemplate['subject'],
            $emailTemplate['html'],
            implode("\r\n", $headers)
        );
        
        if ($mailSent) {
            $errorHandler->logCustomError("Registration email sent successfully", [
                'email' => $email,
                'participant_id' => $participant_id,
                'fullname' => $fullname
            ]);
            return true;
        } else {
            $errorHandler->logCustomError("Failed to send registration email", [
                'email' => $email,
                'participant_id' => $participant_id,
                'fullname' => $fullname
            ]);
            return false;
        }
        
    } catch (Exception $e) {
        $errorHandler->logCustomError("Email sending error", [
            'error' => $e->getMessage(),
            'email' => $email,
            'participant_id' => $participant_id
        ]);
        return false;
    }
}

/**
 * Alternative email sending function using PHPMailer (if available)
 * Uncomment and use this if you have PHPMailer installed
 */
/*
function sendRegistrationEmailWithPHPMailer($fullname, $email, $participant_id) {
    global $errorHandler;
    
    try {
        // Check if PHPMailer is available
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new Exception('PHPMailer not available');
        }
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $fullname);
        $mail->addReplyTo(EVENT_CONTACT, 'YAICESS Support');
        
        // Content
        $emailTemplate = getRegistrationEmailTemplate($fullname, $email, $participant_id);
        $mail->isHTML(true);
        $mail->Subject = $emailTemplate['subject'];
        $mail->Body = $emailTemplate['html'];
        $mail->AltBody = $emailTemplate['text'];
        
        $mail->send();
        
        $errorHandler->logCustomError("Registration email sent successfully via PHPMailer", [
            'email' => $email,
            'participant_id' => $participant_id,
            'fullname' => $fullname
        ]);
        return true;
        
    } catch (Exception $e) {
        $errorHandler->logCustomError("PHPMailer email sending error", [
            'error' => $e->getMessage(),
            'email' => $email,
            'participant_id' => $participant_id
        ]);
        return false;
    }
}
*/

/**
 * Test email functionality
 * @param string $testEmail Email address to send test to
 * @return bool Success status
 */
function testEmailFunctionality($testEmail) {
    return sendRegistrationEmail('Test User', $testEmail, 'TEST123');
}
?> 