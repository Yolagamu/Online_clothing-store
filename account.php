<?php include 'config.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$user_id = $_SESSION['user_id'];
$res     = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user    = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = $conn->real_escape_string($_POST['name']);
  $contact = $conn->real_escape_string($_POST['contact']);
  $billing = $conn->real_escape_string($_POST['billing']);
  $address = $conn->real_escape_string($_POST['address']);
  $conn->query("UPDATE users SET name='$name',contact_number='$contact',billing_info='$billing',address='$address' WHERE id=$user_id");
  header('Location: account.php'); exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Account</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 40px;
    }

    h2 {
      color: linear-gradient(to right, green, pink);
      background: -webkit-linear-gradient(green, pink);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
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

    input, textarea {
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
      font-size: 1rem;
    }

    button:hover {
      background-color: #0056b3;
    }

    .back-button {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #007BFF;
    }

    .back-button:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>Your Account</h2>

  <form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
    <input type="text" name="contact" value="<?= htmlspecialchars($user['contact_number']) ?>">
    <textarea name="billing" placeholder="Billing Info"><?= htmlspecialchars($user['billing_info']) ?></textarea>
    <textarea name="address" placeholder="Address"><?= htmlspecialchars($user['address']) ?></textarea>
    <button type="submit">Update Info</button><br>
    <a href="products.php" class="back-button">← Back</a>
  </form>

</body>
</html>
