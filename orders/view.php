<?php
require '../include/load.php';
checkLogin();

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    redirect('index.php');
}

// 1. Fetch order + customer info
$sql = "SELECT orders.*, users.name, users.email
        FROM orders
        JOIN users ON orders.user_id = users.id
        WHERE orders.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    redirect('index.php');
}

// 2. Fetch order items + product names
$sqlItems = "SELECT order_items.*, products.product_name
             FROM order_items
             JOIN products ON order_items.product_id = products.id
             WHERE order_items.order_id = ?";

$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll();

include '../partials/head.php';
?>

<body>
<?php include '../partials/sidebar.php'; ?>

<div class="content">

    <a href="index.php">← Back to Orders</a>

    <h1>Order #<?= e($order['id']) ?></h1>

    <p><strong>Customer:</strong> <?= e($order['name']) ?> (<?= e($order['email']) ?>)</p>
    <p><strong>Date:</strong> <?= date('d M Y h:i A', strtotime($order['created_at'])) ?></p>
    <p><strong>Status:</strong> <?= e($order['status']) ?></p>

    <h3>Items Ordered</h3>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background:#eee;">
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (at purchase)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['product_name']) ?></td>
                <td><?= e($item['quantity']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top:30px; padding:20px; background:#f9f9f9; border:1px solid #ddd;">
        <h3>Update Status</h3>

        <form action="../api/orders/update.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <label>Status:</label>
            <select name="status">
                <option value="Pending"   <?= $order['status'] === 'Pending'   ? 'selected' : '' ?>>Pending</option>
                <option value="Shipped"   <?= $order['status'] === 'Shipped'   ? 'selected' : '' ?>>Shipped</option>
                <option value="Delivered" <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="Canceled"  <?= $order['status'] === 'Canceled'  ? 'selected' : '' ?>>Canceled</option>
            </select>

            <button type="submit" style="background:blue; color:white;">
                Update Status
            </button>
        </form>
    </div>

</div>

</body>
</html>
