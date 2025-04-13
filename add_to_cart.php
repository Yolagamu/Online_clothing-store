<?php
// add_to_cart.php

require_once 'config.php';
session_start();

// 1) Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id    = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$size       = $_POST['size'];
$color      = $_POST['color'];
$qty        = (int)$_POST['quantity'];

// 2) See if this exact item (same product, size & color) is already in cart
$stmt = $conn->prepare(
    "SELECT id, quantity 
       FROM cart_items 
      WHERE user_id = ? 
        AND product_id = ? 
        AND size = ? 
        AND color = ?"
);
$stmt->bind_param("iiss", $user_id, $product_id, $size, $color);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // 3a) Already in cart → update quantity
    $stmt->bind_result($cart_id, $existing_qty);
    $stmt->fetch();
    $new_qty = $existing_qty + $qty;

    $upd = $conn->prepare(
        "UPDATE cart_items 
            SET quantity = ? 
          WHERE id = ?"
    );
    $upd->bind_param("ii", $new_qty, $cart_id);
    $upd->execute();
    $upd->close();

} else {
    // 3b) Not in cart → insert new row
    $ins = $conn->prepare(
        "INSERT INTO cart_items 
           (user_id, product_id, size, color, quantity) 
         VALUES (?, ?, ?, ?, ?)"
    );
    $ins->bind_param("iissi", $user_id, $product_id, $size, $color, $qty);
    $ins->execute();
    $ins->close();
}

$stmt->close();

// 4) Redirect back to cart (or wherever you like)
header("Location: products.php");
exit;
