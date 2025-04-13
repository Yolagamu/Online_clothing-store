<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'header.php';
?>
<main class="container">
  <h1>Frequently Asked Questions</h1>
  
    <b><i>How do I track my order?</b></i>
	<ul>
   <li>Log in, go to “Account → Orders,” and click “Track.”</li>
    </ul>
	
    <b><i>What is your return policy?</b></i>
	<ul>
    <li>Items can be returned within 30 days. See “Returns” for details.</li>
	</ul>
	<br>
	<a href="products.php" class="back-button">← Back</a>

    <!-- add more Q&A as needed -->
 
</main>
<?php require_once 'footer.php'; ?>
