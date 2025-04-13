<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'header.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT 
      c.id               AS cid,
      p.image            AS img,
      p.price,
      p.available_sizes,
      p.available_colors,
      c.size,
      c.color,
      c.quantity
    FROM cart_items c
    JOIN products   p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<style>
  /* Cart action buttons */
  .cart-actions {
    text-align: right;
    margin-bottom: 5px;
  }
  .cart-actions button {
    background-color: #add8e6; /* light blue */
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 5px;
  }
  .cart-actions button:hover {
    background-color: #87ceeb;
  }
</style>

<h2>Your Cart</h2>

<?php if ($res->num_rows === 0): ?>
  <p>Your cart is empty.</p><br>
  <a href="products.php" class="back-button">← Back to Shop</a>
<?php else: ?>
  <form method="POST" action="update_cart.php">
    <!-- Top‑right buttons, now closer -->
    <div class="cart-actions">
      <button type="submit" name="action" value="update">Update Cart</button>
      <button type="submit" name="action" value="clear">Clear Cart</button>
    </div>

    <table cellpadding="10">
      <?php 
      $total = 0;
      while ($item = $res->fetch_assoc()):
        $sizes     = explode(',', $item['available_sizes']);
        $colors    = explode(',', $item['available_colors']);
        $lineTotal = $item['price'] * $item['quantity'];
        $total    += $lineTotal;
      ?>
      <tr>
        <!-- Product image -->
        <td>
          <img 
            src="images/<?= htmlspecialchars($item['img']) ?>" 
            alt="Product" 
            style="width:80px; object-fit:cover;"
          >
        </td>

        <!-- Size & Color + centered Price -->
        <td style="text-align:center;">
          <div>
            <label>
             
              <select name="sizes[<?= $item['cid'] ?>]">
                <?php foreach ($sizes as $s): $s = trim($s); ?>
                  <option value="<?= htmlspecialchars($s) ?>" <?= $s === $item['size'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
            <label style="margin-left:10px;">
             
              <select name="colors[<?= $item['cid'] ?>]">
                <?php foreach ($colors as $c): $c = trim($c); ?>
                  <option value="<?= htmlspecialchars($c) ?>" <?= $c === $item['color'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <div style="margin-top:8px; font-weight:bold;">
            $<?= number_format($item['price'], 2) ?>
          </div>
        </td>

        <!-- Qty & Delete bin, centered -->
        <td style="text-align:center;">
          <div>
            <label>
              
              <input 
                type="number" 
                name="quantities[<?= $item['cid'] ?>]" 
                value="<?= $item['quantity'] ?>" 
                min="1"
                style="width:50px;"
              >
            </label>
          </div>
          <div style="margin-top:8px;">
            <a href="remove_from_cart.php?id=<?= $item['cid'] ?>" title="Remove">🗑️</a>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

    <!-- Total and Checkout -->
    <div >
      <strong>Total: $<?= number_format($total, 2) ?></strong>
    </div>
    <div >
      <a href="checkout.php">Proceed to Checkout →</a>
    </div>
  </form>
<?php endif; ?>

<?php
$stmt->close();
include 'footer.php';
?>
