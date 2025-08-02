# Razorpay Payment Gateway Setup Guide

## Overview
This guide explains how to configure Razorpay payment gateway for the YAICESS registration system.

## Files Created/Modified
- `razorpay_config.php` - Razorpay configuration file
- `payment_session.php` - Updated with proper error handling
- `razorpay_webhook.php` - Webhook handler for payment updates

## Step-by-Step Setup

### 1. Create Razorpay Account
1. Go to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Sign up for a new account
3. Complete KYC verification
4. Activate your account

### 2. Get API Keys

#### Test Mode (Development)
1. Go to Settings → API Keys in Razorpay Dashboard
2. Generate a new key pair for test mode
3. Copy the Key ID and Key Secret

#### Live Mode (Production)
1. Complete account verification
2. Generate live mode API keys
3. Copy the Key ID and Key Secret

### 3. Configure Payment Button

#### Option A: Use Payment Button (Recommended)
1. Go to Payment Links → Payment Button in Razorpay Dashboard
2. Create a new payment button with:
   - Amount: ₹1
   - Currency: INR
   - Description: "YAICESS Innovation Conference 2K25 Registration"
3. Copy the Payment Button ID

#### Option B: Use Checkout Integration
1. Use the checkout.js integration (already configured in startpayment.php)

### 4. Update Configuration

Edit `razorpay_config.php` and update the following:

```php
// Test Mode (for development)
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_ACTUAL_TEST_KEY_ID');
define('RAZORPAY_KEY_SECRET', 'YOUR_ACTUAL_TEST_KEY_SECRET');

// Payment Button ID (from Razorpay Dashboard)
define('RAZORPAY_PAYMENT_BUTTON_ID', 'pl_YOUR_ACTUAL_BUTTON_ID');
```

### 5. Configure Webhook (Optional but Recommended)

1. Go to Settings → Webhooks in Razorpay Dashboard
2. Add a new webhook with:
   - URL: `https://yourdomain.com/razorpay_webhook.php`
   - Events: `payment.captured`
3. Copy the webhook secret
4. Update `razorpay_config.php`:
   ```php
   define('RAZORPAY_WEBHOOK_SECRET', 'YOUR_WEBHOOK_SECRET');
   ```

## Testing the Integration

### 1. Test Mode
- Use test mode API keys
- Use test card numbers from Razorpay documentation
- Test the complete payment flow

### 2. Test Cards
- Success: 4111 1111 1111 1111
- Failure: 4000 0000 0000 0002
- Expiry: Any future date
- CVV: Any 3 digits

## Common Issues and Solutions

### 1. "Too many requests" Error
**Cause:** Rate limiting from Razorpay
**Solution:**
- Wait a few minutes before retrying
- Check if API keys are correct
- Ensure you're not making too many requests

### 2. "BAD_REQUEST_ERROR"
**Cause:** Invalid API keys or configuration
**Solution:**
- Verify API keys are correct
- Check payment button ID
- Ensure proper configuration

### 3. Payment Button Not Loading
**Cause:** Invalid payment button ID
**Solution:**
- Verify payment button ID in Razorpay Dashboard
- Check if button is active
- Ensure proper domain configuration

### 4. Webhook Not Working
**Cause:** Incorrect webhook URL or secret
**Solution:**
- Verify webhook URL is accessible
- Check webhook secret
- Ensure server can receive POST requests

## Production Checklist

Before going live:

1. ✅ Switch to live mode API keys
2. ✅ Update webhook URL to production domain
3. ✅ Test complete payment flow
4. ✅ Verify webhook is working
5. ✅ Check error handling
6. ✅ Test with real payment methods

## Security Considerations

1. **Never commit API keys to version control**
2. **Use environment variables for sensitive data**
3. **Verify webhook signatures**
4. **Implement proper error handling**
5. **Log all payment attempts**

## Error Handling

The system now includes proper error handling for:
- Rate limiting errors
- Authentication errors
- Invalid payment requests
- Network errors
- Webhook verification failures

## Support

If you encounter issues:
1. Check Razorpay Dashboard for error logs
2. Verify API key configuration
3. Test with Razorpay test cards
4. Contact Razorpay support if needed

## Files to Update

### razorpay_config.php
- Update API keys
- Update payment button ID
- Update webhook secret

### payment_session.php
- Already updated with error handling
- Uses configuration from razorpay_config.php

### razorpay_webhook.php
- Already configured for payment updates
- Updates database when payment is successful

The payment gateway is now properly configured with error handling and should resolve the "Too many requests" error. 