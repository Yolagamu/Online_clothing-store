<?php
session_start();
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$u_res = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $u_res->fetch_assoc();

// Fetch cart
$res = $conn->query("
  SELECT c.id AS cid, p.id AS pid, p.name, p.price, c.size, c.color, c.quantity
  FROM cart_items c
  JOIN products p ON c.product_id = p.id 
  WHERE c.user_id = $user_id
");

$total = 0;
$items = [];
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
    $total += $r['price'] * $r['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment = $_POST['payment_method'];
    $coupon_code = $conn->real_escape_string($_POST['coupon']);
    $coupon_id = 'NULL';

    if ($coupon_code) {
        $c_res = $conn->query("SELECT * FROM coupons WHERE code='$coupon_code' AND expires_at >= CURDATE()");
        if ($c_res->num_rows) {
            $coupon = $c_res->fetch_assoc();
            $coupon_id = $coupon['id'];
            $total *= (1 - $coupon['discount'] / 100);
        }
    }

    $order_number = uniqid('YOL');
    $conn->query("INSERT INTO orders (user_id, total, payment_method, coupon_id, order_number)
                  VALUES ($user_id, $total, '$payment', $coupon_id, '$order_number')");
    $order_id = $conn->insert_id;

    foreach ($items as $i) {
        $conn->query("INSERT INTO order_items (order_id, product_id, size, color, quantity, price)
                      VALUES ($order_id, {$i['pid']}, '{$i['size']}', '{$i['color']}', {$i['quantity']}, {$i['price']})");
    }

    $conn->query("DELETE FROM cart_items WHERE user_id = $user_id");

    // Send confirmation email
    $to = $user['email'];
    $subject = "Order Confirmation - $order_number";
    $message = "Thank you for your order!\nOrder Number: $order_number\nTotal: $" . number_format($total, 2) . "\n\nItems:\n";

    foreach ($items as $i) {
        $message .= "{$i['name']} ({$i['size']}, {$i['color']}) x{$i['quantity']} - $" . number_format($i['price'], 2) . "\n";
    }

    mail($to, $subject, $message);

    echo "<p>Order placed! Your order number is <strong>$order_number</strong>. A confirmation email has been sent.</p>";
    include 'footer.php';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
    }
    form {
      display: inline-block;
      text-align: left;
      margin-top: 20px;
    }
    input, select {
      display: block;
      margin-bottom: 15px;
      padding: 5px;
      width: 250px;
    }
    button {
      background-color: #007bff;
      color: white;
      padding: 8px 16px;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    h2 {
      color: #444;
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<h2>Checkout</h2>
<p>Total: <strong>$<?= number_format($total, 2) ?></strong></p>

<form method="POST" id="checkoutForm">
  <label>Coupon Code:
    <input type="text" name="coupon" placeholder="Enter coupon if any">
  </label>

  <label>Payment Method:
    <select name="payment_method" id="paymentMethod">
      <option value="Cash on Delivery">Cash on Delivery</option>
      <option value="Card">Card</option>
      <option value="PayPal">PayPal</option>
    </select>
  </label>

  <div id="cardDetails" class="hidden">
    <label>Card Number:
      <input type="text" name="card_number" placeholder="1234 5678 9012 3456">
    </label>
    <label>Expiry Date:
      <input type="month" name="expiry_date">
    </label>
    <label>CVV:
      <input type="text" name="cvv" maxlength="4" placeholder="123">
    </label>
  </div>

  <div id="paypalDetails" class="hidden">
    <label>PayPal Email:
      <input type="email" name="paypal_email" placeholder="user@paypal.com">
    </label>
  </div>

  <button type="submit">Confirm Purchase</button>
</form>

<script>
  const paymentSelect = document.getElementById('paymentMethod');
  const cardDetails   = document.getElementById('cardDetails');
  const paypalDetails = document.getElementById('paypalDetails');

  paymentSelect.addEventListener('change', function() {
    cardDetails.classList.add('hidden');
    paypalDetails.classList.add('hidden');

    if (this.value === 'Card') {
      cardDetails.classList.remove('hidden');
    } else if (this.value === 'PayPal') {
      paypalDetails.classList.remove('hidden');
    }
  });
</script>

</body>
</html>

<?php include 'footer.php'; ?>
