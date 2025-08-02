<?php
require 'db_config.php';

echo "<h2>Database Connection Test</h2>";

// Test database connection
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Database connection successful</p>";
}

// Check if database exists
$result = $conn->query("SHOW DATABASES LIKE 'event_db'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Database 'event_db' exists</p>";
} else {
    echo "<p style='color: red;'>❌ Database 'event_db' does not exist</p>";
}

// Check if participants table exists
$result = $conn->query("SHOW TABLES LIKE 'participants'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Table 'participants' exists</p>";
    
    // Show table structure
    $result = $conn->query("DESCRIBE participants");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Table 'participants' does not exist</p>";
    
    // Create the table if it doesn't exist
    echo "<h3>Creating participants table...</h3>";
    $sql = "CREATE TABLE participants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        referral VARCHAR(100),
        payment_status ENUM('pending', 'successful', 'failed') DEFAULT 'pending',
        transaction_id VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✅ Table 'participants' created successfully</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating table: " . $conn->error . "</p>";
    }
}

// Test insert
echo "<h3>Testing Insert...</h3>";
$test_sql = "INSERT INTO participants (fullname, email, phone, username, password, referral) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($test_sql);

if ($stmt) {
    $test_fullname = "Test User";
    $test_email = "test" . time() . "@example.com";
    $test_phone = "1234567890";
    $test_username = "testuser" . time();
    $test_password = md5("testpassword");
    $test_referral = "TEST123";
    
    $stmt->bind_param("ssssss", $test_fullname, $test_email, $test_phone, $test_username, $test_password, $test_referral);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Test insert successful</p>";
        
        // Clean up test data
        $conn->query("DELETE FROM participants WHERE email = '$test_email'");
        echo "<p style='color: blue;'>🗑️ Test data cleaned up</p>";
    } else {
        echo "<p style='color: red;'>❌ Test insert failed: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color: red;'>❌ Prepare statement failed: " . $conn->error . "</p>";
}

$conn->close();
echo "<p><a href='userform.html'>← Back to Registration Form</a></p>";
?> 