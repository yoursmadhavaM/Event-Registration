<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader or manually include classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'rajeshpidathala4gmail@gmail.com';   
    $mail->Password   = 'uprytjikqoeymcpn';     
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                    

    // Recipients
    $mail->setFrom('yourgmail@gmail.com', 'Your Name');
    $mail->addAddress('recipient@example.com', 'Receiver Name');

    // Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'YAICESS SOLUTIONS';
    $mail->Body    = 'This is an <b>automated email</b> using PHPMailer and Gmail SMTP.';
    $mail->AltBody = 'This is an automated email using PHPMailer and Gmail SMTP.';

    $mail->send();
    echo 'Message has been sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>