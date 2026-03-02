<?php
require 'include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('dashboard.php');
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT id, status, created_at 
    FROM orders 
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

include 'partials/head.php';
?>

<div style="display:flex;">

<?php include 'partials/sidebar-user.php'; ?>

<div style="flex:1; padding:30px;">

<h2>My Orders</h2>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Order ID</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach($orders as $order): ?>
<tr>
    <td>#<?= $order['id'] ?></td>
    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
    <td><?= e($order['status']) ?></td>
    <td>
        <a href="user-order-view.php?id=<?= $order['id'] ?>">
            View
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>
</div>