<?php include 'config.php'; session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM cart_items WHERE id=$id AND user_id=".$_SESSION['user_id']);
header('Location: cart.php'); exit;