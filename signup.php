<?php include 'config.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = $conn->real_escape_string($_POST['name']);
  $email   = $conn->real_escape_string($_POST['email']);
  $password= password_hash($_POST['password'], PASSWORD_BCRYPT);
  $contact = $conn->real_escape_string($_POST['contact']);
  $billing = $conn->real_escape_string($_POST['billing']);
  $address = $conn->real_escape_string($_POST['address']);

  $conn->query("INSERT INTO users (name,email,password,contact_number,billing_info,address)
                VALUES ('$name','$email','$password','$contact','$billing','$address')");
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 50px;
    }

    h2 {
      color: red;
    }

    form {
      display: inline-block;
      text-align: left;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
    }

    input, textarea {
      display: block;
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
    }

    button {
      background-color: green;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: darkgreen;
    }
  </style>
</head>
<body>

  <h2>Sign Up</h2>

  <form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="contact" placeholder="Contact Number">
    <textarea name="billing" placeholder="Billing Info"></textarea>
    <textarea name="address" placeholder="Address"></textarea>
    <button type="submit">Sign Up</button>
	<p>already have an account? <a href="login.php">log in</a></p>
  </form>

</body>
</html>
