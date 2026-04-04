<?php
session_start();
require '../include/load.php';
include '../partials/header.php';

checkLogin();

// Cart empty check
if (empty($_SESSION['cart'])) {
    header("Location: ../index.php");
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

<style>
:root {
    --bg-main: #020617;
    --bg-card: rgba(30, 41, 59, 0.5);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-blue: #00f2ff;
    --neon-purple: #a855f7;
    --neon-green: #00ff88;
    --border: rgba(255, 255, 255, 0.08);
    --glass-bg: blur(12px);
}

.light body {
    --bg-main: #f8fafc;
    --bg-card: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --neon-blue: #2563eb;
    --neon-purple: #7c3aed;
    --neon-green: #10b981;
    --border: rgba(0, 0, 0, 0.05);
}

body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }

/* SEAMLESS LAYOUT */
.user-panel-wrapper { display: flex; min-height: 100vh; position: relative; }
.main-content-area { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-width: 0; }
.content-body { padding: 40px; flex-grow: 1; }

.section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 4px; display: block; margin-bottom: 5px; }
.page-title { font-size: 26px; font-weight: 900; color: var(--text-main); margin-bottom: 30px; }

/* CHECKOUT CONTAINER */
.checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; align-items: start; }

.summary-card {
    background: var(--bg-card);
    backdrop-filter: var(--glass-bg);
    border-radius: 24px;
    padding: 30px;
    border: 1px solid var(--border);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.card-title { font-size: 18px; font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
.card-title i { color: var(--neon-purple); }

/* PRODUCT LISTING IN CHECKOUT */
.product-row {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid var(--border);
}
.product-row:last-child { border-bottom: none; }

.prod-img { width: 60px; height: 60px; border-radius: 12px; background: #fff; padding: 5px; object-fit: contain; }

.prod-details { flex: 1; }
.prod-details h4 { font-size: 14px; font-weight: 700; margin: 0; color: var(--text-main); }
.prod-details p { font-size: 12px; color: var(--text-muted); margin: 3px 0 0; }

.prod-price { text-align: right; }
.prod-price span { display: block; font-size: 14px; font-weight: 800; color: var(--neon-blue); }
.prod-qty { font-size: 11px; font-weight: 700; color: var(--text-muted); }

/* TOTAL SECTION */
.total-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px dashed var(--border);
}

.bill-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; font-weight: 600; color: var(--text-muted); }
.bill-row.grand-total { font-size: 20px; font-weight: 900; color: var(--text-main); margin-top: 10px; }
.bill-row.grand-total span { color: var(--neon-green); }

/* PAY BUTTON */
.pay-btn {
    width: 100%;
    margin-top: 25px;
    padding: 16px;
    background: var(--neon-purple);
    color: #fff;
    border: none;
    border-radius: 16px;
    cursor: pointer;
    font-weight: 800;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(168, 85, 247, 0.3);
}

.pay-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(168, 85, 247, 0.5);
    background: #9333ea;
}

/* FOOTER FIX */
.footer-wrapper { width: 100%; margin-top: auto; border-top: 1px solid var(--border); }

@media (max-width: 991px) { 
    .main-content-area { margin-left: 0 !important; } 
    .checkout-grid { grid-template-columns: 1fr; }
}
</style>

<div class="user-panel-wrapper">
    <?php include '../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-8">
                <span class="section-label">Order Finalization</span>
                <h1 class="page-title mt-1 italic">Checkout 🧾</h1>
            </div>

            <div class="checkout-grid">
                
                <div class="summary-card">
                    <h3 class="card-title">Order Items</h3>
                    
                    <div class="product-list">
                        <?php foreach ($products as $p): 
                            $qty = $cart[$p['id']];
                            $subtotal = $p['price'] * $qty;
                            $total += $subtotal;
                        ?>
                        <div class="product-row">
                            <img src="../assets/uploads/<?= $p['image'] ?>" class="prod-img">
                            <div class="prod-details">
                                <h4><?= $p['product_name'] ?></h4>
                                <p><?= substr($p['description'] ?? 'Premium quality product', 0, 50) ?>...</p>
                            </div>
                            <div class="prod-price">
                                <span>₹<?= number_format($subtotal, 2) ?></span>
                                <div class="prod-qty">Qty: <?= $qty ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="summary-card">
                    <h3 class="card-title">Payment Details</h3>
                    
                    <div class="total-section">
                        <div class="bill-row">
                            <span>Subtotal</span>
                            <span>₹<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="bill-row">
                            <span>Shipping</span>
                            <span style="color:var(--neon-green)">FREE</span>
                        </div>
                        <div class="bill-row">
                            <span>Tax (GST)</span>
                            <span>₹0.00</span>
                        </div>
                        
                        <div class="bill-row grand-total">
                            <span>Total</span>
                            <span>₹<?= number_format($total, 2) ?></span>
                        </div>

                        <a href="payment.php" style="text-decoration: none;">
                            <button class="pay-btn">
                                Proceed to Payment →
                            </button>
                        </a>
                        
                        <p style="text-align:center; font-size: 11px; color: var(--text-muted); margin-top: 15px;">
                             Secure Checkout • Encrypted Payment
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="footer-wrapper">
            <?php include '../partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>