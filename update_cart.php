<?php
// update_cart.php

session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Clear entire cart?
if ($_POST['action'] === 'clear') {
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: cart.php');
    exit;
}

// Otherwise, update quantities, sizes, and colors
$quantities = $_POST['quantities'] ?? [];
$sizes      = $_POST['sizes']      ?? [];
$colors     = $_POST['colors']     ?? [];

foreach ($quantities as $cid => $qty) {
    $qty  = (int)$qty;
    $size = $conn->real_escape_string($sizes[$cid]);
    $color= $conn->real_escape_string($colors[$cid]);

    $stmt = $conn->prepare("
      UPDATE cart_items
         SET quantity = ?, size = ?, color = ?
       WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("isiii", $qty, $size, $color, $cid, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to cart
header('Location: cart.php');
exit;
?>
