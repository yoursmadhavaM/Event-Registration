<?php
require 'db_config.php';

echo "<h2>Database Fix Script</h2>";

// Check if transaction_id column exists
$result = $conn->query("SHOW COLUMNS FROM participants LIKE 'transaction_id'");
if ($result->num_rows == 0) {
    echo "❌ transaction_id column does not exist. Adding it now...<br>";
    
    $sql = "ALTER TABLE participants ADD COLUMN transaction_id VARCHAR(255) DEFAULT NULL";
    if ($conn->query($sql)) {
        echo "✅ transaction_id column added successfully!<br>";
    } else {
        echo "❌ Error adding column: " . $conn->error . "<br>";
    }
} else {
    echo "✅ transaction_id column already exists<br>";
}

// Check if payment_status column exists
$result = $conn->query("SHOW COLUMNS FROM participants LIKE 'payment_status'");
if ($result->num_rows == 0) {
    echo "❌ payment_status column does not exist. Adding it now...<br>";
    
    $sql = "ALTER TABLE participants ADD COLUMN payment_status VARCHAR(20) DEFAULT 'pending'";
    if ($conn->query($sql)) {
        echo "✅ payment_status column added successfully!<br>";
    } else {
        echo "❌ Error adding column: " . $conn->error . "<br>";
    }
} else {
    echo "✅ payment_status column already exists<br>";
}

// Show current table structure
echo "<h3>Current Table Structure:</h3>";
$result = $conn->query("DESCRIBE participants");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
}

echo "<p><a href='test_payment_status.php'>Test Payment Status Update</a></p>";
echo "<p><a href='payment_status_update.php'>Go to Payment Status Update</a></p>";
?> 