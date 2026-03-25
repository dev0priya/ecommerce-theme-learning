<?php
require '../../include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('../../dashboard.php');
}

$userId = $_SESSION['user_id'];

/* USER DATA */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

/* STATS */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$userId]);
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(order_items.price * order_items.quantity),0)
    FROM orders
    JOIN order_items ON order_items.order_id = orders.id
    WHERE orders.user_id = ?
");
$stmt->execute([$userId]);
$totalSpent = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM orders 
    WHERE user_id = ? AND status='Pending'
");
$stmt->execute([$userId]);
$pendingOrders = $stmt->fetchColumn();

include '../../partials/head.php';
?>

<?php include '../../partials/header.php'; ?>

<div class="admin-layout">

<?php include '../../partials/sidebar-user.php'; ?>

<div class="content">

<h2>My Account</h2>

<div>
    <h3><?= e($user['name']) ?></h3>
    <p><?= e($user['email']) ?></p>
</div>

<div>
    <p>Total Orders: <?= $totalOrders ?></p>
    <p>Pending Orders: <?= $pendingOrders ?></p>
    <p>Total Spent: $<?= number_format($totalSpent,2) ?></p>
</div>

<br>

<a href="orders.php">👉 View My Orders</a>

</div>
</div>

<?php include '../../partials/footer.php'; ?>