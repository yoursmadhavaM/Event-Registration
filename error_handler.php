<?php
// Simple error handling and logging system
class ErrorHandler {
    private $logFile = 'error_log.txt';
    
    public function __construct() {
        // Set error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', $this->logFile);
    }
    
    // Utility method to log custom errors
    public function logCustomError($message, $context = []) {
        $error = [
            'type' => 'Custom Error',
            'message' => $message,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        $this->logError($error);
    }
    
    private function logError($error) {
        $logEntry = sprintf(
            "[%s] %s: %s\nContext: %s\nURL: %s\n%s\n",
            $error['timestamp'],
            $error['type'],
            $error['message'],
            json_encode($error['context']),
            $error['url'],
            str_repeat('-', 80) . "\n"
        );
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

// Initialize error handler
$errorHandler = new ErrorHandler();

// Function to sanitize user input
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function to validate database connection
function validateDatabase($conn) {
    if (!$conn || $conn->connect_error) {
        return false;
    }
    return true;
}
?> 