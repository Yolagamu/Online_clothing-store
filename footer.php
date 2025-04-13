<footer>
  <div class="footer-links">
    <html>
	<h2 class="brand-heading">Yolanda's Fashion</h2>
	</html>
    <?php if (isset($_SESSION['user_id'])): ?>
      
    <?php endif; ?>
    <a href="faq.php">FAQ</a>
    <a href="returns.php">Returns</a>
    <a href="shipping.php">Shipping Address</a>
	<a href="contact.php">Contact Us</a>
  
  </div>
  <div class="coupons">
    <h4>Available Coupons</h4>
    <?php
    $coupon_res = $conn->query("SELECT * FROM coupons WHERE expires_at >= CURDATE()");
    while ($coupon = $coupon_res->fetch_assoc()):
    ?>
      <div class="coupon">
        <span><?= htmlspecialchars($coupon['code']) ?> - <?= htmlspecialchars($coupon['discount']) ?>% off</span>
      </div>
    <?php endwhile; ?>
  </div>
</footer>
</body>
</html>