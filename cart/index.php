<?php
require '../include/load.php';
include '../partials/head.php';

// If cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h1>Your cart is empty</h1>";
    echo "<a href='../index.php'>Go Back to Shop</a>";
    exit;
}

// 1️⃣ Get product IDs from cart
$ids = array_keys($_SESSION['cart']);

// Create placeholders (?, ?, ?)
$placeholders = implode(',', array_fill(0, count($ids), '?'));

// 2️⃣ Fetch only products in cart
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

$total = 0;
?>

<body>

<h1>Your Shopping Cart</h1>

<table border="1" width="100%" cellpadding="10" cellspacing="0">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <?php
            $qty = $_SESSION['cart'][$p['id']];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;
        ?>
        <tr>
            <td><?= e($p['product_name']) ?></td>
            <td>$<?= e($p['price']) ?></td>
            <td><?= $qty ?></td>
            <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h3>Total: $<?= number_format($total, 2) ?></h3>

<?php if (isset($_SESSION['user_id'])): ?>
    <!-- ✅ Redirect to Stripe payment page -->
    <a href="payment.php"
       style="background:green;color:white;padding:15px;display:inline-block;text-decoration:none;">
        Place Order
    </a>
<?php else: ?>
    <p style="color:red;">
        Please <a href="../sign-in.php">login</a> to checkout.
    </p>
<?php endif; ?>

</body>
</html>
