<?php include 'config.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = $conn->real_escape_string($_POST['email']);
  $password = $_POST['password'];
  $res      = $conn->query("SELECT * FROM users WHERE email='$email'");
  if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      header('Location: products.php');
      exit;
    } else {
      $error = "Invalid credentials";
    }
  } else {
    $error = "Invalid credentials";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 50px;
    }

    h2 {
      color: blue;
    }

    form {
      display: inline-block;
      text-align: left;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
    }

    input {
      display: block;
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
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

    p {
      margin-top: 15px;
    }
  </style>
</head>
<body>
<p><i><b> Welcome back🥰 </i></b></p>
  <h2>Login</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>

  <p>First time? <a href="signup.php">Sign Up</a></p>

</body>
</html>
