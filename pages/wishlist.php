<?php
session_start();
require '../include/load.php';
include '../partials/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<h3 style='text-align:center;'>Please login first</h3>";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ? ORDER BY w.id DESC");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #020617;
    --bg-card: rgba(30, 41, 59, 0.5);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-blue: #00f2ff;
    --neon-pink: #ff007a;
    --border: rgba(255, 255, 255, 0.08);
    --glass-bg: blur(12px);
}

.light body {
    --bg-main: #f8fafc;
    --bg-card: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --neon-blue: #2563eb;
    --neon-pink: #dc2626;
    --border: rgba(0, 0, 0, 0.05);
}

/* 1. SEAMLESS LAYOUT (Same as address.php) */
body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }

.user-panel-wrapper { 
    display: flex; 
    min-height: 100vh; 
    position: relative; 
}

/* 2. MAIN CONTENT AREA FIX */
.main-content-area { 
    flex: 1; 
    margin-left: 280px; /* Sidebar space */
    display: flex; 
    flex-direction: column; 
    min-width: 0; 
}

.content-body { 
    padding: 40px; 
    flex-grow: 1; 
}

/* 3. COMPACT PRODUCT CARDS */
.section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 4px; display: block; margin-bottom: 5px; }
.page-title { font-size: 26px; font-weight: 900; color: var(--text-main); margin-bottom: 30px; }

.p-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

.p-card {
    background: var(--bg-card);
    backdrop-filter: var(--glass-bg);
    border-radius: 20px;
    padding: 15px;
    border: 1px solid var(--border);
    transition: 0.3s ease;
    display: flex;
    flex-direction: column;
}

.p-card:hover {
    transform: translateY(-5px);
    border-color: var(--neon-blue);
    box-shadow: 0 10px 30px rgba(0, 242, 255, 0.1);
}

.p-img-box {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 12px;
}

.p-img-box img { width: 100%; height: 100%; object-fit: contain; }

.p-name { font-size: 14px; font-weight: 700; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.price { font-weight: 800; font-size: 16px; color: var(--neon-blue); margin-bottom: 15px; }

/* 4. NEON BUTTONS */
.btn-group { display: flex; gap: 8px; margin-top: auto; }

.add-cart-btn {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 10px;
    background: var(--neon-blue);
    color: #000;
    cursor: pointer;
    font-weight: 800;
    font-size: 11px;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(0, 242, 255, 0.3);
}

.remove-btn {
    padding: 10px 12px;
    border: 1px solid var(--neon-pink);
    border-radius: 10px;
    background: transparent;
    color: var(--neon-pink);
    cursor: pointer;
    transition: 0.3s;
}

.remove-btn:hover { background: var(--neon-pink); color: #fff; }

/* FOOTER RESET */
.footer-wrapper { width: 100%; margin-top: auto; border-top: 1px solid var(--border); }

/* SweetAlert Custom */
.swal2-popup-custom { background: var(--bg-card) !important; backdrop-filter: blur(15px) !important; border: 1px solid var(--border) !important; color: var(--text-main) !important; }

@media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-5">
                <span class="section-label">Your Collection</span>
                <h1 class="page-title mt-1 italic">Wishlist ❤️</h1>
            </div>

            <?php if (empty($products)): ?>
                <div style="text-align:center; padding: 100px 0; color: var(--text-muted);">
                    <h3>No items found</h3>
                    <p>Your wishlist is currently empty.</p>
                </div>
            <?php else: ?>

            <div class="p-grid">
                <?php foreach ($products as $p): ?>
                    <div class="p-card">
                        <div class="p-img-box">
                            <img src="../assets/uploads/<?= $p['image'] ?>">
                        </div>
                        <h3 class="p-name"><?= $p['product_name'] ?></h3>
                        <div class="price">₹<?= number_format($p['price'], 0) ?></div>

                        <div class="btn-group">
                            <button class="add-cart-btn" data-id="<?= $p['id'] ?>">Add 🛒</button>
                            <button class="remove-btn" data-id="<?= $p['id'] ?>">❌</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
        </div>

        <div class="footer-wrapper">
            <?php include '../partials/footer.php'; ?>
        </div>
    </div>
</div>

<script>
const neonAlert = (title, icon, color) => {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 2000,
        background: 'var(--bg-card)',
        color: 'var(--text-main)',
        iconColor: color,
        customClass: { popup: 'swal2-popup-custom' }
    });
};

document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        let id = btn.dataset.id;
        fetch('../ajax/wishlist_remove.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'product_id=' + id
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                neonAlert('Removed from Wishlist', 'success', 'var(--neon-pink)');
                setTimeout(() => location.reload(), 1000);
            }
        });
    });
});

document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let id = btn.dataset.id;
        if (!cart.includes(id)) {
            cart.push(id);
            localStorage.setItem('cart', JSON.stringify(cart));
            neonAlert('Added to Cart 🛒', 'success', 'var(--neon-blue)');
        } else {
            neonAlert('Already in Cart', 'info', 'var(--neon-blue)');
        }
    });
});
</script>

</body>
</html>