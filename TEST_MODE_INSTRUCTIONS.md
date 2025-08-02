# 🧪 Test Mode Instructions

## Overview
Test mode is now enabled, allowing you to test the complete registration and email functionality without setting up Razorpay payment gateway.

## How to Test

### 1. Complete Registration Flow
1. Go to `userform.html`
2. Fill out the registration form with your details
3. Submit the form
4. You'll receive a registration confirmation email
5. You'll be redirected to the payment page

### 2. Test Payment Flow
1. On the payment page, you'll see a **Test Mode** section
2. Click the **"✅ Complete Registration (Test Mode)"** button
3. The system will simulate payment completion
4. You'll receive a payment confirmation email
5. You'll be redirected to the success page

## What's Tested

### ✅ Registration System
- Form validation
- Database storage
- Session management
- Error handling

### ✅ Email Functionality
- Registration confirmation email
- Payment confirmation email
- Professional email templates
- Event details included

### ✅ Payment Flow
- Payment status updates
- Database updates
- Success page redirect
- Transaction ID generation

### ✅ User Experience
- Smooth navigation
- Error messages
- Loading states
- Success confirmations

## Test Mode Settings

The test mode is controlled by these settings in `razorpay_config.php`:

```php
define('ENABLE_TEST_MODE', true);  // Set to false for production
define('TEST_MODE_SKIP_PAYMENT', true);  // Skip payment in test mode
```

## Switching to Production

When you're ready to go live:

1. **Set up Razorpay account** and get API keys
2. **Update `razorpay_config.php`** with real API keys
3. **Set `ENABLE_TEST_MODE` to `false`**
4. **Test with real payment methods**

## Files Created for Test Mode

- `razorpay_config.php` - Test mode configuration
- `payment_session.php` - Test mode payment interface
- `update_test_payment.php` - Test payment processing
- `TEST_MODE_INSTRUCTIONS.md` - This file

## Email Testing

You can test the email functionality by:
1. Using `test_email.php` to send test emails
2. Completing the registration flow to receive confirmation emails
3. Checking both inbox and spam folder

## Database Updates

Test mode will:
- Update payment status to 'successful'
- Generate test transaction IDs
- Send confirmation emails
- Log all activities

## Security Notes

- Test mode is only active when `ENABLE_TEST_MODE` is true
- Test transactions are clearly marked with 'TEST_' prefix
- No real payment processing occurs in test mode
- All test data is clearly identifiable

## Next Steps

1. **Test the complete flow** using the instructions above
2. **Check email delivery** for both confirmation emails
3. **Verify database updates** in the admin dashboard
4. **Set up Razorpay** when ready for production

The test mode allows you to fully test the registration system without any payment gateway setup! 