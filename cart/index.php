<?php
session_start();
require '../include/load.php';
include '../partials/header.php';

// Empty cart check
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2 style='text-align:center;margin-top:50px;'>Your cart is empty 🛒</h2>";
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

.cart-card {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.cart-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.cart-info {
    flex: 1;
    padding: 0 20px;
}

.qty-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    padding: 6px 12px;
    border: none;
    background: #3b82f6;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
}

.remove-btn {
    background: #ef4444;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.total-box {
    text-align: right;
    margin-top: 30px;
}

.checkout-btn {
    margin-top: 15px;
    padding: 14px 22px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(16,185,129,0.3);
}

.checkout-btn:hover {
    transform: translateY(-2px);
}
</style>

<div class="container">

    <h2>My Cart 🛒</h2>

    <?php foreach ($products as $p): 
        $qty = $cart[$p['id']];
        $subtotal = $p['price'] * $qty;
        $total += $subtotal;
    ?>

    <div class="cart-card">

        <img src="../assets/uploads/<?= $p['image'] ?>" class="cart-img">

        <div class="cart-info">
            <h3><?= $p['product_name'] ?></h3>
            <p>₹<?= number_format($p['price'],2) ?></p>
        </div>

        <div class="qty-box">
            <button class="qty-btn minus" data-id="<?= $p['id'] ?>">-</button>
            <span><?= $qty ?></span>
            <button class="qty-btn plus" data-id="<?= $p['id'] ?>">+</button>
        </div>

        <div>
            ₹<?= number_format($subtotal,2) ?>
        </div>

        <button class="remove-btn" data-id="<?= $p['id'] ?>">Remove</button>

    </div>

    <?php endforeach; ?>

    <div class="total-box">
        <h3>Total: ₹<?= number_format($total,2) ?></h3>

        <!-- ✅ FINAL BUTTON -->
        <a href="checkout.php">
            <button class="checkout-btn">
                Proceed to Checkout →
            </button>
        </a>
    </div>

</div>

<script>

// INCREASE QTY
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

// REMOVE ITEM
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

<?php include '../partials/footer.php'; ?>