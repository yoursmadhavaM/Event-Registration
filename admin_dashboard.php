<?php
require 'db_config.php';
session_start();

// Validate admin login
$username = $_POST['username'] ?? '';
$password = md5($_POST['password'] ?? '');

$query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $participants = $conn->query("SELECT * FROM participants");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Dashboard - Registered Users</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f0f4f8;
                padding: 20px;
            }
            h2 {
                text-align: center;
                color: #0d47a1;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 30px;
            }
            table th, table td {
                padding: 12px;
                border: 1px solid #ccc;
                text-align: center;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }
            .back-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: #0d47a1;
                text-decoration: none;
                font-weight: bold;
            }
            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>📊 Admin Dashboard - Registered Participants</h2>
        <div id="summary" style="font-size:18px;margin-bottom:20px;color:#0d47a1;"></div>
        <div style="margin-bottom:20px;">
            <a href="admin_payment_update.php" style="background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">Manage Payment Status</a>
        </div>
        <table id="participantsTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Referral</th>
                    <th>Registered At</th>
                    <th>Payment Status</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $participants->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['referral']) ?></td>
                    <td><?= $row['registered_at'] ?></td>
                    <td><?= isset($row['payment_status']) ? htmlspecialchars($row['payment_status']) : '' ?></td>
                    <td><?= isset($row['razorpay_order_id']) ? htmlspecialchars($row['razorpay_order_id']) : (isset($row['payment_id']) ? htmlspecialchars($row['payment_id']) : '') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <a href="project.html" class="back-link">← Back to Home</a>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css"/>
    <script>
        $(document).ready(function () {
            var table = $('#participantsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'csvHtml5',
                    'excelHtml5',
                    'print'
                ],
                "createdRow": function(row, data, dataIndex) {
                    // Highlight row if payment_status is not 'successful'
                    var status = data[8]; // 8th column is Payment Status
                    if (status.toLowerCase() !== 'successful') {
                        $(row).css('background-color', '#ffe5e5');
                    }
                }
            });

            // Dashboard summary
            var total = table.rows().count();
            var paid = table.column(8).data().filter(function(value, index) {
                return value.toLowerCase() === 'successful';
            }).length;
            var pending = total - paid;
            $('#summary').html('Total Registered: <b>' + total + '</b> &nbsp; | &nbsp; Paid: <b>' + paid + '</b> &nbsp; | &nbsp; Pending: <b>' + pending + '</b>');
        });
    </script>
    </body>
    </html>
<?php
} else {
    echo "<script>alert('Invalid credentials'); window.location.href='admin_login.html';</script>";
}
$conn->close();
?>
