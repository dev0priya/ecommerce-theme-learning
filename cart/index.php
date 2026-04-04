<?php
session_start();
require '../include/load.php';
include '../partials/header.php';

// Empty cart check
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div style='display:flex; min-height:100vh;'>";
    include '../partials/sidebar-user.php';
    echo "<div style='flex:1; margin-left:280px; padding:100px 40px; text-align:center;'>
            <h2 style='color:var(--text-main); font-size:2rem;'>Your cart is empty 🛒</h2>
            <p style='color:var(--text-muted);'>Add some products to see them here.</p>
          </div></div>";
    include '../partials/footer.php';
    exit;
}

$cart = $_SESSION['cart'];
$ids = array_keys($cart);

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

$total = 0;
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --bg-main: #020617;
    --bg-card: rgba(30, 41, 59, 0.5);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-green: #00ff88;
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
    --neon-green: #10b981;
    --neon-blue: #2563eb;
    --neon-pink: #dc2626;
    --border: rgba(0, 0, 0, 0.05);
}

body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }

/* LAYOUT STRUCTURE (Fixes Sidebar & Footer Overlap) */
.user-panel-wrapper { display: flex; min-height: 100vh; position: relative; }
.main-content-area { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-width: 0; }
.content-body { padding: 40px; flex-grow: 1; }

.section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 4px; display: block; margin-bottom: 5px; }
.page-title { font-size: 26px; font-weight: 900; color: var(--text-main); margin-bottom: 30px; }

/* CART CARDS */
.cart-card {
    background: var(--bg-card);
    backdrop-filter: var(--glass-bg);
    border-radius: 20px;
    padding: 15px 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 1px solid var(--border);
    transition: 0.3s ease;
}

.cart-img-box {
    width: 70px;
    height: 70px;
    background: #fff;
    border-radius: 12px;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-img { width: 100%; height: 100%; object-fit: contain; }

.cart-info { flex: 1; }
.cart-info h3 { font-size: 1rem; font-weight: 700; margin: 0; color: var(--text-main); }
.cart-info p { font-size: 0.9rem; font-weight: 800; color: var(--neon-blue); margin-top: 4px; }

/* QUANTITY BOX */
.qty-box { display: flex; align-items: center; background: rgba(0,0,0,0.1); border-radius: 10px; padding: 4px; border: 1px solid var(--border); }
.qty-btn { width: 28px; height: 28px; border: none; background: transparent; color: var(--text-main); font-weight: 900; cursor: pointer; border-radius: 6px; }
.qty-btn:hover { background: var(--neon-blue); color: #000; }
.qty-val { padding: 0 12px; font-weight: 800; font-size: 13px; }

/* REMOVE BUTTON */
.remove-btn {
    background: transparent;
    color: var(--neon-pink);
    border: 1px solid var(--neon-pink);
    padding: 8px 12px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    transition: 0.3s;
}
.remove-btn:hover { background: var(--neon-pink); color: #fff; }

/* TOTAL & COMPACT CHECKOUT */
.total-box {
    margin-top: 30px;
    padding: 25px;
    background: var(--bg-card);
    border-radius: 20px;
    border: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 30px;
}

.total-box h3 { font-size: 1.2rem; font-weight: 800; margin: 0; }

.checkout-btn {
    padding: 12px 25px; /* Reduced padding for smaller size */
    background: var(--neon-green);
    color: #000;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 800;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(0, 255, 136, 0.2);
    transition: 0.3s;
}

.checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 255, 136, 0.4); }

.footer-wrapper { width: 100%; margin-top: auto; border-top: 1px solid var(--border); }

@media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } .total-box { flex-direction: column; text-align: center; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-5">
                <span class="section-label">Your Selection</span>
                <h1 class="page-title mt-1 italic">My Cart 🛒</h1>
            </div>

            <div class="cart-items-list">
                <?php foreach ($products as $p): 
                    $qty = $cart[$p['id']];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                <div class="cart-card">
                    <div class="cart-img-box">
                        <img src="../assets/uploads/<?= $p['image'] ?>" class="cart-img">
                    </div>

                    <div class="cart-info">
                        <h3><?= $p['product_name'] ?></h3>
                        <p>₹<?= number_format($p['price'], 2) ?></p>
                    </div>

                    <div class="qty-box">
                        <button class="qty-btn minus" data-id="<?= $p['id'] ?>">-</button>
                        <span class="qty-val"><?= $qty ?></span>
                        <button class="qty-btn plus" data-id="<?= $p['id'] ?>">+</button>
                    </div>

                    <div style="font-weight: 800; font-size: 1rem; width: 100px; text-align: right;">
                        ₹<?= number_format($subtotal, 2) ?>
                    </div>

                    <button class="remove-btn" data-id="<?= $p['id'] ?>">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="total-box">
                <h3>Total: <span style="color:var(--neon-green)">₹<?= number_format($total, 2) ?></span></h3>
                <a href="checkout.php">
                    <button class="checkout-btn">Proceed to Checkout →</button>
                </a>
            </div>

        </div>

        <div class="footer-wrapper">
            <?php include '../partials/footer.php'; ?>
        </div>
    </div>
</div>

<script>
// API Logic
document.querySelectorAll('.plus').forEach(btn => {
    btn.addEventListener('click', () => {
        let id = btn.dataset.id;
        fetch('../api/cart/add.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id })
        }).then(() => location.reload());
    });
});

document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        let id = btn.dataset.id;
        fetch('../api/cart/remove.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id })
        }).then(() => location.reload());
    });
});
</script>

</body>
</html>