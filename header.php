<?php
// header.php (or wherever your nav lives)

include 'config.php';

// 1) Ensure session is running
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Calculate cart item count
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
        SELECT SUM(quantity) AS total 
          FROM cart_items 
         WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $cart_count = (int)$row['total'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yolanda's Clothing</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<header class="site-header">
<body>
  <h2 class="brand-heading"><i>Yolanda's Fashion</i>🦋🛍️</h2>

  <?php if (isset($_SESSION['user_id'])): ?>
  <nav class="main-nav">
    <a href="products.php">Home</a> |
    <a href="account.php">Account</a> |
    <!-- 3) Display the cart count badge -->
    <a href="cart.php">
      Cart<?php if ($cart_count > 0): ?> <span class="cart-badge">(<?= $cart_count ?>)</span><?php endif; ?>
    </a> |
    <a href="logout.php">Logout</a>
  </nav>
  <?php endif; ?>
</header>