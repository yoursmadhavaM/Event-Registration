<?php
require 'db_config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get all participants with their payment status
$query = "SELECT id, fullname, email, phone, payment_status, transaction_id, created_at 
          FROM participants 
          ORDER BY created_at DESC";

$result = $conn->query($query);
$participants = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
}

// Find duplicate emails
$emailCounts = [];
$duplicates = [];

foreach ($participants as $participant) {
    $email = $participant['email'];
    if (!isset($emailCounts[$email])) {
        $emailCounts[$email] = [];
    }
    $emailCounts[$email][] = $participant;
}

foreach ($emailCounts as $email => $entries) {
    if (count($entries) > 1) {
        $duplicates[] = [
            'email' => $email,
            'entries' => $entries
        ];
    }
}

// Find successful payments
$successfulPayments = array_filter($participants, function($p) {
    return $p['payment_status'] === 'successful';
});

// Find pending payments
$pendingPayments = array_filter($participants, function($p) {
    return $p['payment_status'] === 'pending';
});

$summary = [
    'total_participants' => count($participants),
    'successful_payments' => count($successfulPayments),
    'pending_payments' => count($pendingPayments),
    'duplicate_emails' => count($duplicates),
    'duplicates' => $duplicates,
    'all_participants' => $participants
];

echo json_encode($summary, JSON_PRETTY_PRINT);

$conn->close();
?> 