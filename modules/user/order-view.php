<?php
require '../../include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('../../dashboard.php');
}

$orderId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$orderId) {
    redirect('dashboard.php');
}

$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    redirect('dashboard.php');
}

$stmt = $pdo->prepare("
    SELECT products.product_name,
           order_items.quantity,
           order_items.price
    FROM order_items
    JOIN products ON products.id = order_items.product_id
    WHERE order_items.order_id = ?
");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

include '../../partials/head.php';
?>

<?php include '../../partials/header.php'; ?>

<div style="display:flex;">
<?php include '../../partials/sidebar-user.php'; ?>

<div style="padding:30px;">

<h2>Order #<?= $order['id'] ?></h2>
<p>Status: <?= e($order['status']) ?></p>

<table border="1" cellpadding="10">
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>

<?php foreach($items as $item): ?>
<tr>
    <td><?= e($item['product_name']) ?></td>
    <td><?= $item['quantity'] ?></td>
    <td>$<?= number_format($item['price'],2) ?></td>
    <td>$<?= number_format($item['price']*$item['quantity'],2) ?></td>
</tr>
<?php endforeach; ?>

</table>

<br>

<?php if ($order['status'] === 'Pending'): ?>
<form method="POST" action="cancel-order.php">
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
    <button type="submit" style="background:red;color:white;">
        Cancel Order
    </button>
</form>
<?php endif; ?>

</div>
</div>

<?php include '../../partials/footer.php'; ?>