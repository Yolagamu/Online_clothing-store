<?php include 'header.php'; ?> 

<style>
  .products {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
  }

  .product {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px;
    width: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .product img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s;
  }

  .product img:hover {
    transform: scale(1.05);
  }

  .product h3 {
    margin: 5px 0;
    font-size: 1.1rem;
  }

  .product p.price {
    margin: 5px 0 10px;
    font-weight: bold;
  }

  .product .options {
    display: flex;
    justify-content: space-between;
    width: 100%;
    gap: 5px;
    margin-bottom: 10px;
  }

  .product .options label {
    flex: 1;
    display: flex;
    flex-direction: column;
    font-size: 0.9rem;
  }

  .product .options select,
  .product .options input {
    width: 100%;
    padding: 4px;
    margin-top: 4px;
    box-sizing: border-box;
  }

  /* MODAL STYLES */
  .modal {
    display: none;
    position: fixed;
    z-index: 999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.8);
  }

  .modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
  }

  .close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover {
    color: #bbb;
  }
</style>

<?php
$search    = $_GET['search'] ?? '';
$sizeF     = $_GET['size'] ?? '';
$colorF    = $_GET['color'] ?? '';
$minPrice  = $_GET['min_price'] ?? '';
$maxPrice  = $_GET['max_price'] ?? '';

$sql    = "SELECT * FROM products WHERE 1";
$params = [];
$types  = '';

if ($search !== '') {
    $sql .= " AND name LIKE ?";
    $params[] = "%{$search}%";
    $types .= 's';
}
if ($minPrice !== '' && is_numeric($minPrice)) {
    $sql .= " AND price >= ?";
    $params[] = (float)$minPrice;
    $types .= 'd';
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $sql .= " AND price <= ?";
    $params[] = (float)$maxPrice;
    $types .= 'd';
}
if ($sizeF !== '') {
    $sql .= " AND FIND_IN_SET(?, available_sizes)";
    $params[] = $sizeF;
    $types .= 's';
}
if ($colorF !== '') {
    $sql .= " AND FIND_IN_SET(?, available_colors)";
    $params[] = $colorF;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$sizeRes  = $conn->query("SELECT available_sizes FROM products");
$sizesAll = [];
while ($r = $sizeRes->fetch_assoc()) {
    foreach (explode(',', $r['available_sizes']) as $sz) {
        $sz = trim($sz);
        if ($sz && !in_array($sz, $sizesAll)) {
            $sizesAll[] = $sz;
        }
    }
}
sort($sizesAll);

$colorRes  = $conn->query("SELECT available_colors FROM products");
$colorsAll = [];
while ($r = $colorRes->fetch_assoc()) {
    foreach (explode(',', $r['available_colors']) as $col) {
        $col = trim($col);
        if ($col && !in_array($col, $colorsAll)) {
            $colorsAll[] = $col;
        }
    }
}
sort($colorsAll);
?>

<h2>Products</h2>

<form method="GET" action="products.php" style="text-align:center; margin-bottom:20px;">
  <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" style="padding:5px; width:200px; margin-right:10px;">
  <button type="submit">Search</button>

  <?php if ($search !== ''): ?>
    <div style="margin-top:15px;">
      <select name="size" style="padding:5px; margin-right:10px;">
        <option value="">All Sizes</option>
        <?php foreach ($sizesAll as $sz): ?>
          <option value="<?= htmlspecialchars($sz) ?>" <?= $sz === $sizeF ? 'selected' : '' ?>><?= htmlspecialchars($sz) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="color" style="padding:5px; margin-right:10px;">
        <option value="">All Colors</option>
        <?php foreach ($colorsAll as $col): ?>
          <option value="<?= htmlspecialchars($col) ?>" <?= $col === $colorF ? 'selected' : '' ?>><?= htmlspecialchars($col) ?></option>
        <?php endforeach; ?>
      </select>

      <input type="number" step="0.01" name="min_price" placeholder="Min Price" value="<?= htmlspecialchars($minPrice) ?>" style="padding:5px; width:80px; margin-right:10px;">
      <input type="number" step="0.01" name="max_price" placeholder="Max Price" value="<?= htmlspecialchars($maxPrice) ?>" style="padding:5px; width:80px; margin-right:10px;">

      <a href="products.php" style="margin-left:10px;">Clear</a>
    </div>
  <?php endif; ?>
</form>

<!-- Modal HTML -->
<div id="imgModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

<!-- Product Cards -->
<div class="products">
  <?php if ($res && $res->num_rows > 0): ?>
    <?php while ($p = $res->fetch_assoc()):
      $sizes  = $p['available_sizes']  ? explode(',', $p['available_sizes'])  : [];
      $colors = $p['available_colors'] ? explode(',', $p['available_colors']) : [];
    ?>
      <div class="product">
        <img 
          src="images/<?= htmlspecialchars($p['image']) ?>" 
          alt="<?= htmlspecialchars($p['name']) ?>" 
          onclick="openModal(this.src)"
        >
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p class="price">$<?= number_format($p['price'], 2) ?></p>

        <form method="POST" action="add_to_cart.php">
          <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">

          <div class="options">
            <label>Size
              <select name="size" required>
                <?php foreach ($sizes as $s): ?>
                  <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>Color
              <select name="color" required>
                <?php foreach ($colors as $c): ?>
                  <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>Qty
              <input type="number" name="quantity" value="1" min="1" required>
            </label>
          </div>

          <button type="submit">Add to Cart</button>
        </form>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No products found.</p>
  <?php endif; ?>
</div>

<script>

  function openModal(src) {
    document.getElementById("imgModal").style.display = "block";
    document.getElementById("modalImage").src = src;
  }

  function closeModal() {
    document.getElementById("imgModal").style.display = "none";
  }

  // Close modal if clicked outside the image
  window.onclick = function(event) {
    const modal = document.getElementById("imgModal");
    if (event.target === modal) {
      closeModal();
    }
  }
</script>

<?php include 'footer.php'; ?>
