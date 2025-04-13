<?php
// order_details.php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "Order ID not provided.";
    exit;
}

$order_id = intval($_GET['id']);

// Get order info
$stmt = $conn->prepare("
    SELECT o.*, u.name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<p>Order not found.</p>";
    exit;
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name AS product_name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<h2>Order #<?= $order_id ?> Details</h2>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['name']) ?> (<?= $order['email'] ?>)</p>
<p><strong>Total:</strong> R<?= number_format($order['total_amount'], 2) ?></p>
<p><strong>Date:</strong> <?= $order['created_at'] ?></p>

<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>Product</th>
    <th>Size</th>
    <th>Color</th>
    <th>Quantity</th>
    <th>Unit Price</th>
    <th>Line Total</th>
  </tr>
  <?php while ($item = $items->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($item['product_name']) ?></td>
      <td><?= $item['size'] ?></td>
      <td><?= $item['color'] ?></td>
      <td><?= $item['quantity'] ?></td>
      <td>R<?= number_format($item['price'], 2) ?></td>
      <td>R<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<a href="admin_orders.php">← Back to Orders</a>

<?php include 'footer.php'; ?>
