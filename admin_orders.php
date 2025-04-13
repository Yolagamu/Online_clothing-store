<?php
session_start();
include 'config.php';

// Only allow admin access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$query = "SELECT orders.id, users.name, orders.order_number, orders.total_price, orders.payment_method, orders.created_at 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Orders - Yolanda's Clothing</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Admin Panel - Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Order Number</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['order_number']) ?></td>
            <td>R<?= number_format($row['total_price'], 2) ?></td>
            <td><?= htmlspecialchars($row['payment_method']) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</body>
</html>
