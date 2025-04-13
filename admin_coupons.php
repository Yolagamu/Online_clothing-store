<?php include 'header.php'; session_start();
// TODO: Restrict to admin users only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code     = $conn->real_escape_string($_POST['code']);
  $discount = floatval($_POST['discount']);
  $expires  = $_POST['expires'];
  $conn->query("INSERT INTO coupons (code,discount,expires_at)
                VALUES ('$code',$discount,'$expires')");
}
$res = $conn->query("SELECT * FROM coupons");
?>
<h2>Manage Coupons</h2>
<form method="POST">
  <input type="text" name="code" placeholder="Coupon Code" required>
  <input type="number" step="0.01" name="discount" placeholder="Discount %" required>
  <input type="date" name="expires" required>
  <button type="submit">Add Coupon</button>
</form>
<table>
  <tr><th>Code</th><th>Discount</th><th>Expires</th></tr>
  <?php while ($c = $res->fetch_assoc()): ?>
  <tr>
    <td><?=$c['code']?></td>
    <td><?=$c['discount']?></td>
    <td><?=$c['expires_at']?></td>
  </tr>
  <?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>