<?php
// Razorpay Configuration
// Replace these with your actual Razorpay API keys

// Test Mode (for development)
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_TEST_KEY_ID');  // Replace with your test key ID
define('RAZORPAY_KEY_SECRET', 'YOUR_TEST_KEY_SECRET');    // Replace with your test key secret

// Live Mode (for production)
// define('RAZORPAY_KEY_ID', 'rzp_live_YOUR_LIVE_KEY_ID');  // Replace with your live key ID
// define('RAZORPAY_KEY_SECRET', 'YOUR_LIVE_KEY_SECRET');    // Replace with your live key secret

// Payment Button ID (from Razorpay Dashboard)
define('RAZORPAY_PAYMENT_BUTTON_ID', 'pl_R08csZoyThJfOj');  // Your payment button ID

// Webhook Secret (for webhook verification)
define('RAZORPAY_WEBHOOK_SECRET', 'YOUR_WEBHOOK_SECRET');  // Replace with your webhook secret

// Payment Settings
define('PAYMENT_AMOUNT', 100);  // Amount in paise (₹1 = 100 paise)
define('PAYMENT_CURRENCY', 'INR');
define('PAYMENT_DESCRIPTION', 'YAICESS Innovation Conference 2K25 Registration');

// Test Mode Settings (for development without Razorpay)
define('ENABLE_TEST_MODE', true);  // Set to true to bypass payment while getting real keys
define('TEST_MODE_SKIP_PAYMENT', true);  // Skip payment in test mode

// Error Messages
define('RAZORPAY_ERROR_MESSAGES', [
    'BAD_REQUEST_ERROR' => 'Invalid request. Please try again.',
    'RATE_LIMIT_ERROR' => 'Too many requests. Please wait a moment and try again.',
    'AUTHENTICATION_ERROR' => 'Authentication failed. Please contact support.',
    'INVALID_PAYMENT_ID' => 'Invalid payment ID. Please try again.',
    'PAYMENT_FAILED' => 'Payment failed. Please try again.',
    'DEFAULT_ERROR' => 'An error occurred. Please try again or contact support.'
]);

/**
 * Get Razorpay API instance
 */
function getRazorpayApi() {
    require_once 'vendor/autoload.php';
    
    return new \Razorpay\Api\Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
}

/**
 * Create a new order
 */
function createRazorpayOrder($receipt, $amount = null) {
    try {
        $api = getRazorpayApi();
        
        $orderData = [
            'receipt' => $receipt,
            'amount' => $amount ?? PAYMENT_AMOUNT,
            'currency' => PAYMENT_CURRENCY,
            'payment_capture' => 1
        ];
        
        $order = $api->order->create($orderData);
        return $order;
        
    } catch (Exception $e) {
        error_log("Razorpay order creation failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Verify payment signature
 */
function verifyPaymentSignature($attributes) {
    try {
        $api = getRazorpayApi();
        $api->utility->verifyPaymentSignature($attributes);
        return true;
    } catch (Exception $e) {
        error_log("Payment signature verification failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Get error message for Razorpay error code
 */
function getRazorpayErrorMessage($errorCode) {
    return RAZORPAY_ERROR_MESSAGES[$errorCode] ?? RAZORPAY_ERROR_MESSAGES['DEFAULT_ERROR'];
}

/**
 * Check if Razorpay is properly configured
 */
function isRazorpayConfigured() {
    // If test mode is enabled and skip payment is true, return true
    if (ENABLE_TEST_MODE && TEST_MODE_SKIP_PAYMENT) {
        return true;
    }
    
    return !empty(RAZORPAY_KEY_ID) && 
           !empty(RAZORPAY_KEY_SECRET) && 
           RAZORPAY_KEY_ID !== 'rzp_test_YOUR_TEST_KEY_ID' &&
           RAZORPAY_KEY_SECRET !== 'YOUR_TEST_KEY_SECRET';
}

/**
 * Check if test mode is enabled
 */
function isTestMode() {
    return ENABLE_TEST_MODE && TEST_MODE_SKIP_PAYMENT;
}
?> 