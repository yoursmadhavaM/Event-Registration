# Email Setup Guide for YAICESS Registration System

## Overview
This guide explains how to set up email functionality for the YAICESS registration system. When users successfully register, they will receive a confirmation email with event details.

## Files Created
- `email_config.php` - Email configuration and templates
- `send_email.php` - Email sending functions
- `test_email.php` - Test page to verify email functionality
- Modified `register.php` - Now includes email sending after successful registration

## Configuration Steps

### 1. Email Server Configuration
Edit `email_config.php` and update the following settings:

```php
// For Gmail SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');  // Your Gmail address
define('SMTP_PASSWORD', 'your-app-password');     // Gmail App Password
define('SMTP_FROM_EMAIL', 'noreply@yaicess.com');
define('SMTP_FROM_NAME', 'YAICESS Innovation Conference');
```

### 2. Gmail Setup (Recommended)
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a new app password for "Mail"
3. Use this app password in `SMTP_PASSWORD`

### 3. Alternative: Using PHP's Built-in Mail Function
If you don't want to configure SMTP, the system will use PHP's built-in `mail()` function. This requires:
- A properly configured mail server on your hosting
- Or use services like SendGrid, Mailgun, etc.

### 4. Event Details
The email template includes these event details:
- **Event Name:** YAICESS Innovation Conference 2K25
- **Date:** July 30, 2025
- **Time:** 10:00 AM - 6:00 PM
- **Location:** Hyderabad, India
- **Contact:** info@techconf2025.com

You can modify these in `email_config.php`.

## Testing the Email Functionality

### 1. Use the Test Page
Visit `test_email.php` in your browser and enter an email address to test the functionality.

### 2. Test Registration Flow
1. Go to the registration form (`userform.html`)
2. Fill out the form with a valid email
3. Submit the registration
4. Check the email inbox for the confirmation email

## Email Template Features

### HTML Email Includes:
- ✅ Professional styling with gradient header
- ✅ Event details and agenda
- ✅ Registration ID and user information
- ✅ Important instructions for attendees
- ✅ Contact information
- ✅ Mobile-responsive design

### Text Version:
- ✅ Plain text alternative for email clients that don't support HTML
- ✅ All the same information as the HTML version

## Troubleshooting

### Common Issues:

1. **Emails not sending:**
   - Check your SMTP configuration
   - Verify your hosting supports email sending
   - Check error logs in `error_log.txt`

2. **Emails going to spam:**
   - Configure proper SPF/DKIM records for your domain
   - Use a reputable email service (Gmail, SendGrid, etc.)
   - Avoid spam trigger words in subject lines

3. **PHPMailer alternative:**
   - Uncomment the PHPMailer function in `send_email.php`
   - Install PHPMailer via Composer: `composer require phpmailer/phpmailer`

### Error Logging:
All email sending attempts are logged in the error handler. Check `error_log.txt` for detailed information about email sending success/failure.

## Production Recommendations

1. **Use a reliable email service:**
   - SendGrid, Mailgun, or Amazon SES
   - Better deliverability and tracking

2. **Implement email queuing:**
   - For high-volume registrations
   - Prevents timeout issues

3. **Add email templates to database:**
   - Makes it easier to update content
   - Supports multiple languages

4. **Add email tracking:**
   - Track open rates and click rates
   - Monitor delivery success

## Security Considerations

1. **Never commit email credentials to version control**
2. **Use environment variables for sensitive data**
3. **Implement rate limiting for email sending**
4. **Validate email addresses before sending**

## Files Modified

### register.php
- Added email sending after successful registration
- Includes error logging for email success/failure
- Maintains existing functionality while adding email feature

The email functionality is now fully integrated into the registration system. Users will receive a professional confirmation email with all event details when they successfully register. 