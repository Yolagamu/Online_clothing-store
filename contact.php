<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'header.php';
?>
<main class="container">
  <h1>Contact Us</h1>
  <p>
    Have a question? Email us at<br></p>
    <p><a href="mailto:info@yolandasclothing.com">📩info@yolandasclothing.com</a></p><br>
     ☎call <strong>+1‑800‑123‑4567</strong>.</p>
   <a href="products.php" class="back-button">← Back</a>
  
</main>
<?php require_once 'footer.php'; ?>
