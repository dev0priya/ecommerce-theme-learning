<?php
require 'include/load.php';

// --- CONFIGURATION & LOGIC ---
$limit = 12;
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['q'] ?? '';

// Fetch products with category names
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
/* --- SHARED & DARK THEME (DEFAULT) --- */
body {
    background-color: #010413;
    color: #ffffff;
    font-family: 'Inter', sans-serif;
    transition: background 0.3s;
}

.main-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 20px 60px 20px;
}

/* SEARCH BAR */
.search-container {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
    margin-top: 10px;
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
    transition: 0.3s;
}

.search-icon-inside {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 20px;
}

/* SECTION HEADER */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 800;
    text-transform: uppercase;
    position: relative;
    padding-bottom: 8px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 4px;
    background: #2563eb;
    border-radius: 10px;
}

/* PRODUCT CARD */
.p-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.p-card {
    background: #0b1120; 
    border-radius: 28px;
    padding: 18px;
    border: 1px solid rgba(255, 255, 255, 0.03);
    transition: transform 0.3s ease;
}

.p-card:hover {
    transform: translateY(-8px);
    border-color: rgba(37, 99, 235, 0.3);
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

.cat-label {
    text-transform: uppercase;
    color: #3b82f6;
    font-size: 0.7rem;
    font-weight: 800;
    margin-bottom: 6px;
    display: block;
}

.name-price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.p-name { font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0; }

.p-price-badge {
    background: #2563eb;
    color: #fff;
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: 800;
}

.discount-row { display: flex; align-items: center; gap: 10px; }

.p-old-price { color: #475569; text-decoration: line-through; font-size: 0.85rem; }

.p-save-tag {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 800;
}

/* --- LIGHT THEME OVERRIDES --- */
.light body { background-color: #f8fafc; color: #0f172a; }

.light .search-wrapper input {
    background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a;
}

.light .section-title { color: #0f172a; }

.light .p-card {
    background: #ffffff; border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
}

.light .p-name { color: #1e293b; }

.light .p-old-price { color: #94a3b8; }

.light .p-save-tag { background: #dcfce7; color: #15803d; }

/* PAGINATION */
.pagination-container {
    display: flex; justify-content: center; align-items: center; margin-top: 50px; gap: 8px;
}

.page-btn {
    display: flex; align-items: center; justify-content: center; min-width: 45px; height: 45px;
    padding: 0 15px; background: #0b1120; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px; color: #94a3b8; text-decoration: none; font-weight: 700;
}

.light .page-btn { background: #fff; border: 1px solid #e2e8f0; color: #64748b; }

.page-btn.active { background: #2563eb; color: #fff; border-color: #2563eb; }

.page-btn.disabled { opacity: 0.4; pointer-events: none; }
</style>

<div class="main-container">
    
    <div class="search-container">
        <form action="" method="GET" class="search-wrapper">
            <iconify-icon icon="lucide:search" class="search-icon-inside"></iconify-icon>
            <input type="text" name="q" placeholder="Search premium products..." value="<?= e($search) ?>">
        </form>
    </div>

    <div class="section-header">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <a href="#" style="color: #64748b; text-decoration: none; font-size: 0.85rem; font-weight: 700;">VIEW ALL →</a>
    </div>

    <div class="p-grid">
        <?php foreach ($products as $p): ?>
            <div class="p-card">
                <div class="p-img-box">
                    <img src="assets/uploads/<?= e($p['image']) ?>" alt="<?= e($p['product_name']) ?>">
                </div>

                <span class="cat-label"><?= e($p['cat_name'] ?? 'ELECTRONICS') ?></span>

                <div class="name-price-row">
                    <h3 class="p-name"><?= e($p['product_name']) ?></h3>
                    <div class="p-price-badge">₹<?= number_format($p['price'], 0) ?></div>
                </div>

                <div class="discount-row">
                    <span class="p-old-price">₹<?= number_format($p['price'] * 1.2, 0) ?></span>
                    <span class="p-save-tag">SAVE 20%</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination-container">
        <a href="?page=<?= max(1, $page - 1) ?>&q=<?= urlencode($search) ?>" class="page-btn <?= ($page <= 1) ? 'disabled' : '' ?>">
            <iconify-icon icon="lucide:chevron-left"></iconify-icon> Prev
        </a>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="page-btn <?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a href="?page=<?= min($total_pages, $page + 1) ?>&q=<?= urlencode($search) ?>" class="page-btn <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            Next <iconify-icon icon="lucide:chevron-right"></iconify-icon>
        </a>
    </div>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>