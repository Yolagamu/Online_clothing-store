<?php include 'config.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) header('Location: login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $order_id  = $conn->real_escape_string($_POST['order_id']);
  $product   = $conn->real_escape_string($_POST['product']);
  $size      = $conn->real_escape_string($_POST['size']);
  $color     = $conn->real_escape_string($_POST['color']);
  $reason    = $conn->real_escape_string($_POST['reason']);
  $comments  = $conn->real_escape_string($_POST['comments']);
  $user_id   = $_SESSION['user_id'];

  $conn->query("INSERT INTO returns (user_id, order_id, product, size, color, reason, comments)
                VALUES ('$user_id', '$order_id', '$product', '$size', '$color', '$reason', '$comments')");

  $success = "Your return request has been submitted!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Return Product</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 40px;
    }

    h2 {
      color: teal;
    }

    form {
      display: inline-block;
      text-align: left;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      max-width: 400px;
      width: 100%;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      box-sizing: border-box;
      font-size: 1rem;
    }

    button {
      background-color: #007BFF;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .message {
      color: green;
      font-weight: bold;
    }

    .back-button {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #007BFF;
    }
  </style>
</head>
<body>

  <h2>Return a Product</h2>

  <?php if (isset($success)) echo "<p class='message'>$success</p>"; ?>

  <form method="POST">
    <input type="text" name="order_id" placeholder="Order Number" required>
    <input type="text" name="product" placeholder="Product Name" required>
    <input type="text" name="size" placeholder="Size">
    <input type="text" name="color" placeholder="Color">
    
    <select name="reason" required>
      <option value="">-- Select Reason --</option>
      <option value="Wrong size">Wrong size</option>
      <option value="Wrong color">Wrong color</option>
      <option value="Damaged item">Damaged item</option>
      <option value="Not as described">Not as described</option>
      <option value="Other">Other</option>
    </select>

    <textarea name="comments" placeholder="Additional Comments (optional)"></textarea>

    <button type="submit">Submit Return</button>
    <br>
    <a href="products.php" class="back-button">← Back to Products</a>
  </form>

</body>
</html>
