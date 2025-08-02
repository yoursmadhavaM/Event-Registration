<?php
require 'db_config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";
if ($conn->ping()) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
}

echo "<h2>Table Structure Check</h2>";
$result = $conn->query("DESCRIBE participants");
if ($result) {
    echo "✅ Table 'participants' exists<br>";
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ Table 'participants' does not exist<br>";
}

echo "<h2>Sample Data Check</h2>";
$result = $conn->query("SELECT id, fullname, email, payment_status, transaction_id FROM participants LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "✅ Found {$result->num_rows} participants<br>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th><th>Payment Status</th><th>Transaction ID</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['fullname']}</td><td>{$row['email']}</td><td>{$row['payment_status']}</td><td>{$row['transaction_id']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ No participants found in database<br>";
}

echo "<h2>Test Payment Status Update</h2>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $transaction_id = $_POST['transaction_id'];
    
    echo "Testing update for email: $email<br>";
    echo "Transaction ID: $transaction_id<br>";
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, payment_status FROM participants WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "✅ User found - ID: {$user['id']}, Current status: {$user['payment_status']}<br>";
        
        if ($user['payment_status'] == 'pending') {
            // Try to update
            $update_stmt = $conn->prepare("UPDATE participants SET payment_status='successful', transaction_id=? WHERE email=? AND payment_status='pending'");
            $update_stmt->bind_param("ss", $transaction_id, $email);
            
            if ($update_stmt->execute()) {
                echo "✅ Payment status updated successfully!<br>";
                echo "Rows affected: " . $update_stmt->affected_rows . "<br>";
            } else {
                echo "❌ Error updating: " . $update_stmt->error . "<br>";
            }
            $update_stmt->close();
        } else {
            echo "❌ Payment status is already: {$user['payment_status']}<br>";
        }
    } else {
        echo "❌ Email not found in database<br>";
    }
    $stmt->close();
}
?>

<form method="POST">
    <h3>Test Payment Status Update</h3>
    <p>Email: <input type="email" name="email" required></p>
    <p>Transaction ID: <input type="text" name="transaction_id" required></p>
    <p><input type="submit" value="Test Update"></p>
</form>

<p><a href="payment_status_update.php">← Back to Payment Status Update</a></p> 