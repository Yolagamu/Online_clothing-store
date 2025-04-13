<?php include 'config.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = $conn->real_escape_string($_POST['full_name']);
  $phone     = $conn->real_escape_string($_POST['phone']);
  $address   = $conn->real_escape_string($_POST['address']);
  $city      = $conn->real_escape_string($_POST['city']);
  $postal    = $conn->real_escape_string($_POST['postal']);
  $country   = $conn->real_escape_string($_POST['country']);

  $conn->query("INSERT INTO shipping_details (user_id, full_name, phone, address, city, postal_code, country)
                VALUES ('$user_id', '$full_name', '$phone', '$address', '$city', '$postal', '$country')");

  $success = "Shipping information saved!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shipping Details</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 40px;
    }

    h2 {
      color: orange;
    }

    form {
      display: inline-block;
      text-align: left;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      max-width: 450px;
      width: 100%;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      font-size: 1rem;
      box-sizing: border-box;
    }

    button {
      background-color: purple;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }

    button:hover {
      background-color: darkmagenta;
    }

    .message {
      color: green;
      font-weight: bold;
    }

    .back-link {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #007BFF;
    }
  </style>
</head>
<body>

<h2>Shipping Information</h2>

<?php if (isset($success)) echo "<p class='message'>$success</p>"; ?>

<form method="POST">
  <input type="text" name="full_name" placeholder="Full Name" required>
  <input type="text" name="phone" placeholder="Phone Number" required>
  <input type="text" name="address" placeholder="Street Address" required>
  <input type="text" name="city" placeholder="City" required>
  <input type="text" name="postal" placeholder="Postal Code" required>
  <input type="text" name="country" placeholder="Country" required>

  <button type="submit">Save Shipping Info</button>
  <br>
  <a href="checkout.php" class="back-link">← Back to Checkout</a>
</form>

</body>
</html>
