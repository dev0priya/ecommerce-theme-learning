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
/* -----------------------------------------------------------
   1. PREMIUM THEME VARIABLES
----------------------------------------------------------- */
body {
    --bg-main: #020617;
    --bg-card: #0f172a;
    --text-main: #f1f5f9;
    --text-muted: #94a3b8;
    --accent: #3b82f6;
    --price-bg: #10b981; /* Emerald Green for Price Box */
    --border: rgba(255, 255, 255, 0.05);
    --shadow-card: 0 10px 30px -10px rgba(0,0,0,0.5);

    background-color: var(--bg-main);
    color: var(--text-main);
    font-family: 'Inter', sans-serif;
    transition: 0.3s ease;
}

.light body {
    --bg-main: #f8fafc;
    --bg-card: #ffffff;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --border: #e2e8f0;
    --price-bg: #059669; /* Slightly darker green for light mode */
    --shadow-card: 0 10px 40px -15px rgba(0,0,0,0.06);
}

.main-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px 80px 20px;
}

/* -----------------------------------------------------------
   2. SEARCH & HEADER
----------------------------------------------------------- */
.search-container { display: flex; justify-content: center; margin-bottom: 50px; }
.search-wrapper { position: relative; width: 100%; max-width: 500px; }
.search-wrapper input {
    width: 100%; background: var(--bg-card); border: 1px solid var(--border);
    padding: 14px 50px; border-radius: 50px; color: var(--text-main);
    box-shadow: var(--shadow-card);
}
.search-icon-inside { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 20px; }

.section-title { font-size: 1.8rem; font-weight: 800; margin-bottom: 30px; letter-spacing: -0.03em; }

/* -----------------------------------------------------------
   3. PRODUCT CARD DESIGN
----------------------------------------------------------- */
.p-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

.p-card {
    background: var(--bg-card);
    border-radius: 24px;
    padding: 16px;
    border: 1px solid var(--border);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: var(--shadow-card);
}

.p-card:hover {
    transform: translateY(-8px);
    border-color: var(--accent);
}

/* Image Box */
.p-img-box {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 18px;
    overflow: hidden;
    background: #fff; 
    margin-bottom: 15px;
    position: relative;
}

.p-img-box img { width: 100%; height: 100%; object-fit: contain; transition: 0.5s; }
.p-card:hover .p-img-box img { transform: scale(1.05); }

/* -----------------------------------------------------------
   4. ICON BUTTONS (Wishlist & Cart)
----------------------------------------------------------- */
.action-icon {
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
    text-decoration: none; /* Links ke liye underline hatane ke liye */
}

/* Wishlist - Default State (Black Outline & White Bg) */
.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #cb8c8c;
    color: #000000; /* Dark Icon */
    font-size: 18px;
    z-index: 5;
    border: 1px solid #f6f8f9; /* Light Black/Grey Outline */
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Wishlist - Hover State (Red) */
.wishlist-btn:hover { 
    color: #fefefe; 
    transform: scale(1.1); 
    background: #ef2424; 
    border-color: #ef4444; 
}

/* Add to Cart - Bottom Right on Card */
.cart-icon-btn {
    position: absolute;
    bottom: 16px;
    right: 16px;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--accent);
    color: white;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    opacity: 0;
    transform: translateY(10px);
}
.p-card:hover .cart-icon-btn { opacity: 1; transform: translateY(0); }
.cart-icon-btn:hover { background: #2563eb; transform: scale(1.1); }

/* -----------------------------------------------------------
   5. PRICE & DETAILS
----------------------------------------------------------- */
.card-info { padding-right: 50px; } /* Space for cart icon */

.cat-tag { color: var(--accent); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; margin-bottom: 5px; display: block; }
.p-name { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 10px 0; line-height: 1.3; }

.price-container { display: flex; align-items: center; gap: 8px; margin-top: auto; }

/* THE GREEN PRICE BOX */
.price-badge {
    background: var(--price-bg);
    color: #fff;
    padding: 4px 12px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 1rem;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}

.old-price { color: var(--text-muted); text-decoration: line-through; font-size: 0.8rem; }

/* -----------------------------------------------------------
   6. PAGINATION
----------------------------------------------------------- */
.pagination { display: flex; justify-content: center; margin-top: 50px; gap: 8px; }
.page-link {
    width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
    background: var(--bg-card); border-radius: 10px; color: var(--text-main); text-decoration: none;
    border: 1px solid var(--border); font-weight: 600;
}
.page-link.active { background: var(--accent); color: white; border-color: var(--accent); }
</style>

<div class="main-container">
    
    <div class="search-container">
        <form action="" method="GET" class="search-wrapper">
            <iconify-icon icon="lucide:search" class="search-icon-inside"></iconify-icon>
            <input type="text" name="q" placeholder="Search premium products..." value="<?= e($search) ?>">
        </form>
    </div>

    <h2 class="section-title">Trending Now</h2>

    <div class="p-grid">
        <?php foreach ($products as $p): ?>
            <div class="p-card">
                
                <div class="p-img-box">
                    <img src="assets/uploads/<?= e($p['image']) ?>" alt="<?= e($p['product_name']) ?>">
                    
                    <button class="action-icon wishlist-btn wish-btn" data-product-id="<?= $p['id'] ?>">
                     <iconify-icon icon="lucide:heart"></iconify-icon>
                    </button>
                </div>

                <div class="card-info">
                    <span class="cat-tag"><?= e($p['cat_name'] ?? 'General') ?></span>
                    <h3 class="p-name"><?= e($p['product_name']) ?></h3>

                    <div class="price-container">
                        <span class="price-badge">₹<?= number_format($p['price'], 0) ?></span>
                        <span class="old-price">₹<?= number_format($p['price'] * 1.2, 0) ?></span>
                    </div>
                </div>

                <button class="action-icon cart-icon-btn add-btn" data-id="<?= $p['id'] ?>" title="Add to Cart">
                   <iconify-icon icon="lucide:shopping-cart"></iconify-icon>
                </button>

            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <a href="?page=<?= max(1, $page - 1) ?>&q=<?= urlencode($search) ?>" class="page-link">
            <iconify-icon icon="lucide:chevron-left"></iconify-icon>
        </a>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="page-link <?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a href="?page=<?= min($total_pages, $page + 1) ?>&q=<?= urlencode($search) ?>" class="page-link">
            <iconify-icon icon="lucide:chevron-right"></iconify-icon>
        </a>
    </div>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>

    <script>
$(document).ready(function() {

    /* =========================
       ❤️ WISHLIST
    ========================= */
    $('.wish-btn').on('click', function() {
        const btn = $(this);
        const pid = btn.data('product-id');

        $.post('ajax/wishlist_add.php', { product_id: pid }, function(res) {

            console.log(res);

            if (res.status === 'success') {

                btn.find('iconify-icon')
                   .attr('icon', 'solar:heart-bold')
                   .css('color', 'red');

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Added to Wishlist ❤️',
                    html: '<a href="pages/wishlist.php" style="color:#2563eb; font-weight:600;">Tap to view</a>',
                    showConfirmButton: false,
                    timer: 2500
                });
            }

            else if (res.status === 'exists') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Already in Wishlist',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

            else if (res.message === 'please_login') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to save items'
                });
            }

            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message || 'Something went wrong'
                });
            }

        }, 'json')

        .fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Request failed. Try again.'
            });
        });
    });


    /* =========================
       🛒 ADD TO CART
    ========================= */
    $('.add-btn, .cart-icon-btn').on('click', function(e) {

        e.preventDefault(); // IMPORTANT (link ko rokta hai)

        const btn = $(this);
        const pid = btn.data('id');

        fetch('api/cart/add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: pid })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === 'success') {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Product added to cart 🛒',
                    html: '<a href="cart/index.php" style="color:#2563eb; font-weight:600;">Tap to view</a>',
                    showConfirmButton: false,
                    timer: 2500
                });

                // navbar count update
                if (data.cart_count) {
                    $('.cart-badge').text(data.cart_count);
                }
            }

            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add product'
                });
            }

        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Server Error'
            });
        });

    });

});
</script>