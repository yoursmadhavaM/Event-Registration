<?php
// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');  // Change to your SMTP server
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');  // Change to your email
define('SMTP_PASSWORD', 'your-app-password');  // Change to your app password
define('SMTP_FROM_EMAIL', 'noreply@yaicess.com');
define('SMTP_FROM_NAME', 'YAICESS Innovation Conference');

// Event Details
define('EVENT_NAME', 'YAICESS Innovation Conference 2K25');
define('EVENT_DATE', 'July 30, 2025');
define('EVENT_TIME', '10:00 AM - 6:00 PM');
define('EVENT_LOCATION', 'Hyderabad, India');
define('EVENT_CONTACT', 'info@techconf2025.com');

// Email Templates
function getRegistrationEmailTemplate($fullname, $email, $participant_id) {
    $subject = "Registration Confirmation - " . EVENT_NAME;
    
    $htmlBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Registration Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .success-icon { font-size: 48px; margin-bottom: 20px; }
            .event-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4caf50; }
            .registration-id { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 15px 0; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; margin: 10px 5px; }
            .important { background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='success-icon'>✓</div>
                <h1>Registration Successful!</h1>
                <p>Welcome to " . EVENT_NAME . "</p>
            </div>
            
            <div class='content'>
                <h2>Dear " . htmlspecialchars($fullname) . ",</h2>
                
                <p>Thank you for registering for <strong>" . EVENT_NAME . "</strong>! Your registration has been successfully received.</p>
                
                <div class='registration-id'>
                    <strong>Registration ID:</strong> " . $participant_id . "<br>
                    <strong>Email:</strong> " . htmlspecialchars($email) . "
                </div>
                
                <div class='event-details'>
                    <h3>Event Details:</h3>
                    <p><strong>Event:</strong> " . EVENT_NAME . "</p>
                    <p><strong>Date:</strong> " . EVENT_DATE . "</p>
                    <p><strong>Time:</strong> " . EVENT_TIME . "</p>
                    <p><strong>Location:</strong> " . EVENT_LOCATION . "</p>
                    <p><strong>Contact:</strong> " . EVENT_CONTACT . "</p>
                </div>
                
                <div class='important'>
                    <h4>📋 Important Information:</h4>
                    <ul>
                        <li>Please complete your payment to confirm your registration</li>
                        <li>You will receive a payment confirmation email once payment is completed</li>
                        <li>Please arrive 30 minutes before the event starts</li>
                        <li>Bring a valid ID for verification</li>
                    </ul>
                </div>
                
                <h3>Event Agenda:</h3>
                <ul>
                    <li><strong>09:00 AM</strong> - Registration & Welcome Coffee</li>
                    <li><strong>10:00 AM</strong> - Opening Ceremony & Keynote</li>
                    <li><strong>11:30 AM</strong> - AI Trends Panel Discussion</li>
                    <li><strong>01:00 PM</strong> - Lunch & Networking</li>
                    <li><strong>02:30 PM</strong> - Technical Workshops</li>
                    <li><strong>04:30 PM</strong> - Project Showcase</li>
                    <li><strong>06:00 PM</strong> - Closing Remarks & Awards</li>
                </ul>
                
                <p>We look forward to seeing you at the event!</p>
                
                <p>Best regards,<br>
                <strong>YAICESS Team</strong></p>
            </div>
            
            <div class='footer'>
                <p>This is an automated email. Please do not reply to this message.</p>
                <p>For support, contact: " . EVENT_CONTACT . "</p>
            </div>
        </div>
    </body>
    </html>";
    
    $textBody = "
    Registration Successful - " . EVENT_NAME . "
    
    Dear " . $fullname . ",
    
    Thank you for registering for " . EVENT_NAME . "! Your registration has been successfully received.
    
    Registration ID: " . $participant_id . "
    Email: " . $email . "
    
    Event Details:
    - Event: " . EVENT_NAME . "
    - Date: " . EVENT_DATE . "
    - Time: " . EVENT_TIME . "
    - Location: " . EVENT_LOCATION . "
    - Contact: " . EVENT_CONTACT . "
    
    Important Information:
    - Please complete your payment to confirm your registration
    - You will receive a payment confirmation email once payment is completed
    - Please arrive 30 minutes before the event starts
    - Bring a valid ID for verification
    
    Event Agenda:
    - 09:00 AM - Registration & Welcome Coffee
    - 10:00 AM - Opening Ceremony & Keynote
    - 11:30 AM - AI Trends Panel Discussion
    - 01:00 PM - Lunch & Networking
    - 02:30 PM - Technical Workshops
    - 04:30 PM - Project Showcase
    - 06:00 PM - Closing Remarks & Awards
    
    We look forward to seeing you at the event!
    
    Best regards,
    YAICESS Team
    
    For support, contact: " . EVENT_CONTACT;
    
    return [
        'subject' => $subject,
        'html' => $htmlBody,
        'text' => $textBody
    ];
}
?> 