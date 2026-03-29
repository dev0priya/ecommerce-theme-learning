<?php
require '../include/load.php';
checkLogin();

// Fetch orders with customer name (JOIN)
$sql = "SELECT orders.*, users.name AS customer_name
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.created_at DESC";

$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll();

include '../partials/head.php';
?>

<body>
<?php
// Option A: Using your existing BASE_URL logic (Recommended)
if ($_SESSION['user_role'] === 'admin') {
    include $_SERVER['DOCUMENT_ROOT'] . '/ecommerce-theme-learning/partials/sidebar-admin.php';
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/ecommerce-theme-learning/partials/sidebar-user.php';
}
?>

<div class="content">
    <h1>Order Management</h1>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background:#eee;">
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= e($order['id']) ?></td>
                <td><?= e($order['customer_name']) ?></td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td>$<?= number_format($order['total_amount'], 2) ?></td>

                <td style="font-weight:bold; color: <?= $order['status'] === 'Pending' ? 'orange' : 'green' ?>">
                    <?= e($order['status']) ?>
                </td>

                <td>
                    <a href="view.php?id=<?= $order['id'] ?>" style="color:blue;">
                        View Details
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
