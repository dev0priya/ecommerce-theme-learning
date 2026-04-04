<?php
session_start();
require '../include/load.php';
include '../partials/header.php';

checkLogin();

// cart empty check
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

<style>
body {
    background: #f8fafc;
    font-family: 'Inter', sans-serif;
}

.container {
    max-width: 1100px;
    margin: auto;
    padding: 40px 20px;
}

.checkout-card {
    background: #fff;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.checkout-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.total-box {
    text-align: right;
    margin-top: 20px;
}

.pay-btn {
    margin-top: 20px;
    padding: 14px 22px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(99,102,241,0.3);
}

.pay-btn:hover {
    transform: translateY(-2px);
}
</style>

<div class="container">

    <h2>Checkout 🧾</h2>

    <div class="checkout-card">
        <h3>Order Summary</h3>

        <?php foreach ($products as $p): 
            $qty = $cart[$p['id']];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;
        ?>

        <div class="checkout-item">
            <span><?= $p['product_name'] ?> (x<?= $qty ?>)</span>
            <span>₹<?= number_format($subtotal,2) ?></span>
        </div>

        <?php endforeach; ?>

        <div class="total-box">
            <h3>Total: ₹<?= number_format($total,2) ?></h3>

            <!-- ✅ NEXT STEP -->
            <a href="payment.php">
                <button class="pay-btn">
                    Proceed to Payment →
                </button>
            </a>
        </div>
    </div>

</div>

<?php include '../partials/footer.php'; ?>