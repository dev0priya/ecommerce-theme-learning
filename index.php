<?php
// 1. Load the engine (NO checkLogin here)
require 'include/load.php';

/* ================================
   PAGINATION CONFIG
================================ */
$limit = 12; // products per page
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
$offset = ($page - 1) * $limit;

/* ================================
   SEARCH + PAGINATION LOGIC
================================ */
$search = $_GET['q'] ?? '';

if ($search) {

    // Count filtered products
    $countStmt = $pdo->prepare(
        "SELECT COUNT(*) FROM products WHERE product_name LIKE ?"
    );
    $countStmt->execute(["%$search%"]);
    $total_items = $countStmt->fetchColumn();

    // Fetch paginated search results
    $stmt = $pdo->prepare(
        "SELECT * FROM products
         WHERE product_name LIKE ?
         ORDER BY created_at DESC
         LIMIT ? OFFSET ?"
    );
    $stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();

} else {

    // Count all products
    $countStmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_items = $countStmt->fetchColumn();

    // Fetch paginated products
    $stmt = $pdo->prepare(
        "SELECT * FROM products
         ORDER BY created_at DESC
         LIMIT ? OFFSET ?"
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$products = $stmt->fetchAll();
$total_pages = ceil($total_items / $limit);

include 'partials/head.php';
?>

<body>

<?php include 'partials/header.php'; ?>

<!-- 🔍 SEARCH BAR (TOP, not bottom) -->
<form action="index.php" method="GET" class="search-bar" style="padding:20px;">
    <input type="text"
           name="q"
           placeholder="Search products..."
           value="<?= e($_GET['q'] ?? '') ?>">

    <button type="submit">Search</button>

    <?php if ($search): ?>
        <a href="index.php" style="color:red; margin-left:10px;">Clear</a>
    <?php endif; ?>
</form>

<!-- 🛍 PRODUCT GRID -->
<div style="display:flex; flex-wrap:wrap; gap:20px; padding:20px;">
    <?php if ($products): ?>
        <?php foreach ($products as $p): ?>
            <div style="border:1px solid #ddd; padding:10px; width:200px; border-radius:5px;">
                <img src="assets/uploads/<?= e($p['image']) ?>"
                     style="width:100%; height:150px; object-fit:cover;">

                <h3><?= e($p['product_name']) ?></h3>
                <p>$<?= e($p['price']) ?></p>

                <button onclick="addToCart(<?= $p['id'] ?>)"
                        style="background:blue; color:white; width:100%; padding:5px; cursor:pointer;">
                    Add to Cart
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<!-- 📄 PAGINATION -->
<?php if ($total_pages > 1): ?>
<div class="pagination" style="text-align:center; margin-bottom:30px;">
    <?php if ($page > 1): ?>
        <a href="index.php?page=<?= $page - 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>">
            ← Previous
        </a>
    <?php endif; ?>

    <span style="margin:0 15px;">
        Page <?= $page ?> of <?= $total_pages ?>
    </span>

    <?php if ($page < $total_pages): ?>
        <a href="index.php?page=<?= $page + 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>">
            Next →
        </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<script>
function addToCart(productId) {
    fetch('api/cart/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Added to cart!');
            location.reload();
        }
    });
}

</script>

</body>
</html>
