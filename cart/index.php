<?php
require 'include/load.php';

// --- CONFIGURATION & LOGIC ---
$limit = 12;
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['q'] ?? '';

$baseQuery = "FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.product_name LIKE ?";
$params = ["%$search%"];

$countStmt = $pdo->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute($params);
$total_items = $countStmt->fetchColumn();

$stmt = $pdo->prepare("SELECT p.*, c.name as cat_name $baseQuery ORDER BY p.id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $params[0], PDO::PARAM_STR);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll();
$total_pages = ceil($total_items / $limit);

include 'partials/header.php'; 
?>

<style>
body {
    background-color: #010413;
    color: #ffffff;
    font-family: 'Inter', sans-serif;
}

.main-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 20px 60px 20px;
}

.search-container {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
}

.search-wrapper {
    position: relative;
    width: 100%;
    max-width: 550px;
}

.search-wrapper input {
    width: 100%;
    background: #0b1120;
    border: 1px solid rgba(255,255,255,0.1);
    padding: 14px 20px 14px 50px;
    border-radius: 12px;
    color: #fff;
}

.search-icon-inside {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 35px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 800;
}

.p-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.p-card {
    position: relative;
    background: #0b1120;
    border-radius: 28px;
    padding: 18px;
}

.p-img-box {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 22px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 18px;
}

.p-img-box img {
    width: 100%; height: 100%; object-fit: cover;
}

.p-actions {
    position: absolute;
    top: 12px;
    right: 12px;
}

.wishlist-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    background: rgba(0,0,0,0.6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.wishlist-btn:hover {
    background: #ef4444;
}

.name-price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}

.p-name { font-size: 1.2rem; font-weight: 700; }

.p-price-badge {
    background: #2563eb;
    padding: 6px 14px;
    border-radius: 10px;
}

.add-cart-btn {
    width: 100%;
    margin-top: 14px;
    padding: 10px;
    border-radius: 10px;
    border: none;
    background: #2563eb;
    color: #fff;
    font-weight: 700;
    display: flex;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
}

.add-cart-btn:hover {
    background: #1d4ed8;
}
</style>

<div class="main-container">

    <div class="search-container">
        <form action="" method="GET" class="search-wrapper">
            <iconify-icon icon="lucide:search" class="search-icon-inside"></iconify-icon>
            <input type="text" name="q" placeholder="Search products..." value="<?= e($search) ?>">
        </form>
    </div>

    <div class="section-header">
        <h2 class="section-title">NEW ARRIVALS</h2>
    </div>

    <div class="p-grid">
        <?php foreach ($products as $p): ?>
            <div class="p-card">

                <div class="p-actions">
                    <button class="wishlist-btn" data-id="<?= $p['id'] ?>">
                        <iconify-icon icon="mdi:heart-outline"></iconify-icon>
                    </button>
                </div>

                <div class="p-img-box">
                    <img src="assets/uploads/<?= e($p['image']) ?>">
                </div>

                <div class="name-price-row">
                    <h3 class="p-name"><?= e($p['product_name']) ?></h3>
                    <div class="p-price-badge">₹<?= number_format($p['price'], 0) ?></div>
                </div>

                <button class="add-cart-btn" data-id="<?= $p['id'] ?>">
                    <iconify-icon icon="mdi:cart-outline"></iconify-icon>
                    Add to Cart
                </button>

            </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        alert('Added to cart: ' + id);
    });
});

document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        alert('Added to wishlist: ' + id);
    });
});
</script>

<?php include 'partials/footer.php'; ?>