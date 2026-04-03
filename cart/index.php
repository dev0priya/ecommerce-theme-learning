<?php
require 'include/load.php';

// --- CONFIGURATION & LOGIC ---
$limit = 12;
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['q'] ?? '';

$baseQuery = "FROM products WHERE product_name LIKE ?";
$params = ["%$search%"];

$countStmt = $pdo->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute($params);
$total_items = $countStmt->fetchColumn();

// Bind products
$stmt = $pdo->prepare("SELECT * $baseQuery ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll();
$total_pages = ceil($total_items / $limit);

include 'partials/header.php'; 
?>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
:root {
    --p-indigo: #6366f1;
    --p-indigo-dark: #4f46e5;
    --p-rose: #f43f5e;
    --p-dark: #0f172a;
    --p-border: #e2e8f0;
}

body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
.main-container { max-width: 1400px; margin: 0 auto; padding: 50px 20px; }

/* SEARCH */
.search-section { display: flex; justify-content: center; margin-bottom: 50px; }
.search-input-group { position: relative; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-radius: 50px; }
.search-input-group input { width: 100%; padding: 15px 25px 15px 50px; border-radius: 50px; border: 1px solid var(--p-border); outline: none; }

/* GRID & CARD */
.p-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 35px; }
.p-card { background: white; border-radius: 30px; padding: 12px; border: 1px solid var(--p-border); transition: 0.4s; position: relative; display: flex; flex-direction: column; }
.p-card:hover { transform: translateY(-10px); box-shadow: 0 25px 50px rgba(99, 102, 241, 0.15); border-color: var(--p-indigo); }

.p-img-box { width: 100%; aspect-ratio: 1/1; border-radius: 22px; overflow: hidden; background: linear-gradient(135deg, #eef2ff, #f8fafc); }
.p-img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }

/* ❤️ WISHLIST BUTTON */
.wish-btn {
    position: absolute; top: 20px; right: 20px; z-index: 10; width: 44px; height: 44px;
    background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);
    border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: 0.3s; box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}
.wish-btn iconify-icon { color: #64748b; font-size: 22px; }
.wish-btn:hover { border-color: var(--p-rose); transform: scale(1.1); }
.wish-btn.active { background: var(--p-rose); border-color: var(--p-rose); }
.wish-btn.active iconify-icon { color: #fff; }

/* CONTENT & PRICE */
.p-content { padding: 15px 5px; text-align: center; flex-grow: 1; }
.p-name { font-weight: 800; color: var(--p-dark); font-size: 1.1rem; }
.p-price { font-weight: 900; color: var(--p-indigo); font-size: 1.4rem; margin: 10px 0; display: block; }

/* 🛒 ADD TO CART BUTTON */
.add-btn {
    width: 100%; padding: 12px; border-radius: 18px; border: none; font-weight: 700;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(135deg, var(--p-indigo), var(--p-indigo-dark));
    color: white; cursor: pointer; transition: 0.3s;
}
.add-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(99, 102, 241, 0.3); }

/* PAGINATION */
.pagination-wrap { display: flex; justify-content: center; margin-top: 60px; gap: 10px; }
.page-link { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid var(--p-border); border-radius: 12px; text-decoration: none; color: var(--p-dark); font-weight: 700; }
.page-link.active { background: var(--p-indigo); color: white; }
</style>

<div class="main-container">
    <div class="search-section">
        <form action="" method="GET" class="search-input-group">
            <iconify-icon icon="lucide:search" class="search-icon"></iconify-icon>
            <input type="text" name="q" placeholder="Search products..." value="<?= e($search) ?>">
        </form>
    </div>

    <div class="p-grid">
        <?php foreach ($products as $p): ?>
            <div class="p-card">
                <button class="wish-btn" data-product-id="<?= $p['id'] ?>">
                    <iconify-icon icon="solar:heart-linear"></iconify-icon>
                </button>

                <div class="p-img-box">
                    <img src="assets/uploads/<?= e($p['image']) ?>" alt="<?= e($p['product_name']) ?>">
                </div>

                <div class="p-content">
                    <h3 class="p-name"><?= e($p['product_name']) ?></h3>
                    <span class="p-price">₹<?= number_format($p['price'], 2) ?></span>

                    <button class="add-btn" data-id="<?= $p['id'] ?>">
                        <iconify-icon icon="solar:cart-large-minimalistic-bold"></iconify-icon>
                        Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination-wrap">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="page-link <?= ($page == $i) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // 1. Wishlist Logic
    $('.wish-btn').on('click', function() {
        const btn = $(this);
        const pid = btn.data('product-id');

        $.ajax({
            url: 'actions/wishlist_add.php', // Aapka existing link
            method: 'POST',
            data: { product_id: pid },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    btn.addClass('active');
                    btn.find('iconify-icon').attr('icon', 'solar:heart-bold');
                    
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Saved to Wishlist', showConfirmButton: false, timer: 2000
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: res.message || 'Something went wrong!' });
                }
            }
        });
    });

    // 2. Add to Cart Logic
    $('.add-btn').on('click', function() {
        const pid = $(this).data('id');
        
        $.ajax({
            url: 'actions/cart_add.php', // Add to cart ka backend link
            method: 'POST',
            data: { product_id: pid, quantity: 1 },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Added to cart successfully', showConfirmButton: false, timer: 1500
                    });
                    // Agar header mein cart count update karna ho:
                    if(res.cart_count) $('.cart-badge').text(res.cart_count);
                }
            }
        });
    });
});
</script>

<?php include 'partials/footer.php'; ?>