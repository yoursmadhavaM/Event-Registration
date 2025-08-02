<?php
require 'db_config.php';
session_start();

// Simple admin check (you can enhance this)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.html');
    exit;
}

// Handle payment status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_payment'])) {
    $user_id = $_POST['user_id'];
    $payment_status = $_POST['payment_status'];
    $transaction_id = $_POST['transaction_id'];
    
    $stmt = $conn->prepare("UPDATE participants SET payment_status=?, transaction_id=? WHERE id=?");
    $stmt->bind_param("ssi", $payment_status, $transaction_id, $user_id);
    
    if ($stmt->execute()) {
        $message = "Payment status updated successfully!";
    } else {
        $error = "Error updating payment status: " . $conn->error;
    }
    $stmt->close();
}

// Get all participants
$participants = $conn->query("SELECT * FROM participants ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Payment Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; }
        .form-group { margin: 10px 0; }
        .btn { padding: 8px 15px; background: #007bff; color: white; border: none; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Status Management</h1>
        
        <?php if (isset($message)): ?>
            <div class="success"><?= $message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Current Status</th>
                    <th>Transaction ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $participants->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['payment_status']) ?></td>
                    <td><?= htmlspecialchars($row['transaction_id'] ?? '') ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <select name="payment_status" required>
                                <option value="pending" <?= $row['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="successful" <?= $row['payment_status'] == 'successful' ? 'selected' : '' ?>>Successful</option>
                                <option value="failed" <?= $row['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                            </select>
                            <input type="text" name="transaction_id" placeholder="Transaction ID" value="<?= htmlspecialchars($row['transaction_id'] ?? '') ?>">
                            <button type="submit" name="update_payment" class="btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <p><a href="admin_dashboard.php">← Back to Admin Dashboard</a></p>
    </div>
</body>
</html> 