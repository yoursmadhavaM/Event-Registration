# 🔑 Real Razorpay Setup Guide

## Overview
This guide will help you set up real Razorpay payment gateway for production use.

## Step-by-Step Setup

### 1. Create Razorpay Account
1. Go to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Click "Sign Up" and create a new account
3. Complete your business verification (KYC)
4. Activate your account

### 2. Get Your API Keys

#### For Live Mode (Production)
1. **Login to Razorpay Dashboard**
2. **Go to Settings → API Keys**
3. **Click "Generate Key Pair"**
4. **Copy both Key ID and Key Secret**
   - Key ID starts with `rzp_live_`
   - Key Secret is a long string

### 3. Create Payment Button

#### Option A: Payment Button (Recommended)
1. Go to **Payment Links → Payment Button**
2. Click **"Create Payment Button"**
3. Configure the button:
   - **Amount**: ₹1
   - **Currency**: INR
   - **Description**: "YAICESS Innovation Conference 2K25 Registration"
   - **Button Text**: "Pay ₹1"
4. **Copy the Payment Button ID** (starts with `pl_`)

#### Option B: Use Checkout Integration
- The system already supports checkout integration
- No additional setup needed

### 4. Update Configuration

Edit `razorpay_config.php` and replace the placeholder values:

```php
// Live Mode (for production)
define('RAZORPAY_KEY_ID', 'rzp_live_YOUR_ACTUAL_LIVE_KEY_ID');
define('RAZORPAY_KEY_SECRET', 'YOUR_ACTUAL_LIVE_KEY_SECRET');

// Payment Button ID (from Razorpay Dashboard)
define('RAZORPAY_PAYMENT_BUTTON_ID', 'pl_YOUR_ACTUAL_BUTTON_ID');
```

### 5. Configure Webhook (Recommended)

1. Go to **Settings → Webhooks** in Razorpay Dashboard
2. Click **"Add New Webhook"**
3. Configure:
   - **URL**: `https://yourdomain.com/razorpay_webhook.php`
   - **Events**: Select `payment.captured`
4. **Copy the webhook secret**
5. Update `razorpay_config.php`:
   ```php
   define('RAZORPAY_WEBHOOK_SECRET', 'YOUR_WEBHOOK_SECRET');
   ```

## Testing with Real Keys

### Test Cards for Live Mode
- **Success**: Use any real card
- **Failure**: Use expired cards or insufficient funds
- **Note**: Real charges will be made in live mode

### Test the Complete Flow
1. Go to `userform.html`
2. Fill out registration form
3. Complete payment with real card
4. Check email confirmations
5. Verify database updates

## Security Checklist

### ✅ Before Going Live
1. **Use HTTPS** on your domain
2. **Verify webhook signature** in production
3. **Set up proper error logging**
4. **Test with small amounts first**
5. **Monitor payment logs**

### ✅ API Key Security
1. **Never commit keys to version control**
2. **Use environment variables** if possible
3. **Rotate keys regularly**
4. **Monitor for unauthorized usage**

## Production Configuration

### Final `razorpay_config.php` Setup:
```php
<?php
// Live Mode Configuration
define('RAZORPAY_KEY_ID', 'rzp_live_abc123def456');  // Your live key ID
define('RAZORPAY_KEY_SECRET', 'your_live_secret_key');  // Your live secret
define('RAZORPAY_PAYMENT_BUTTON_ID', 'pl_xyz789abc123');  // Your button ID
define('RAZORPAY_WEBHOOK_SECRET', 'your_webhook_secret');  // Your webhook secret

// Disable test mode
define('ENABLE_TEST_MODE', false);
define('TEST_MODE_SKIP_PAYMENT', false);

// Payment Settings
define('PAYMENT_AMOUNT', 100);  // ₹1 in paise
define('PAYMENT_CURRENCY', 'INR');
define('PAYMENT_DESCRIPTION', 'YAICESS Innovation Conference 2K25 Registration');
?>
```

## Troubleshooting

### Common Issues:

1. **"Invalid API Key" Error**
   - Verify key ID and secret are correct
   - Ensure you're using live keys for live mode
   - Check if account is activated

2. **Payment Button Not Loading**
   - Verify payment button ID is correct
   - Check if button is active in dashboard
   - Ensure domain is properly configured

3. **Webhook Not Working**
   - Verify webhook URL is accessible
   - Check webhook secret is correct
   - Ensure server can receive POST requests

4. **Payment Failing**
   - Check card details are correct
   - Verify sufficient funds
   - Check if card supports online payments

## Support

### Razorpay Support
- **Email**: care@razorpay.com
- **Phone**: 1800-419-1833
- **Documentation**: https://razorpay.com/docs/

### Your System Support
- Check `error_log.txt` for detailed errors
- Monitor Razorpay dashboard for payment logs
- Test with small amounts first

## Next Steps

1. **Get your Razorpay account** and API keys
2. **Update the configuration** with real keys
3. **Test with small amounts** first
4. **Monitor the system** for any issues
5. **Go live** when everything works perfectly

The system is now ready for real payments! Just update the configuration with your actual Razorpay keys. 