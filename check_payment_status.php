<?php
require 'db_config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get participant ID from request
$participant_id = $_GET['participant_id'] ?? null;

if (!$participant_id) {
    echo json_encode(['error' => 'Participant ID is required']);
    exit();
}

// Check payment status for the participant
$stmt = $conn->prepare("SELECT payment_status, transaction_id FROM participants WHERE id = ?");
$stmt->bind_param("i", $participant_id);
$stmt->execute();
$stmt->bind_result($payment_status, $transaction_id);
$stmt->fetch();
$stmt->close();

if ($payment_status) {
    echo json_encode([
        'status' => $payment_status,
        'transaction_id' => $transaction_id,
        'participant_id' => $participant_id
    ]);
} else {
    echo json_encode(['error' => 'Participant not found']);
}

$conn->close();
?> 