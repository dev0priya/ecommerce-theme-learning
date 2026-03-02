<?php
require 'include/load.php';
checkLogin();

/* =========================
   DASHBOARD METRICS LOGIC
========================= */

// Total Users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Total Products
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Total Orders
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Total Revenue (sum of all order_items)
$totalRevenue = $pdo->query("
    SELECT COALESCE(SUM(price * quantity),0) 
    FROM order_items
")->fetchColumn();

// Pending Orders
$pendingOrders = $pdo->query("
    SELECT COUNT(*) FROM orders WHERE status = 'Pending'
")->fetchColumn();

// Last 7 Days New Users
$newUsers7Days = $pdo->query("
    SELECT COUNT(*) 
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
")->fetchColumn();

// Last 30 Days Sales
$sales30Days = $pdo->query("
    SELECT COALESCE(SUM(order_items.price * order_items.quantity),0)
    FROM orders
    JOIN order_items ON order_items.order_id = orders.id
    WHERE orders.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
")->fetchColumn();

include 'partials/head.php';
?>

<body>

<div class="admin-layout">

<?php
if ($_SESSION['user_role'] === 'admin') {
    include 'partials/sidebar-admin.php';
} else {
    include 'partials/sidebar-user.php';
}
?>

<div class="content">

<div class="page-header">
    <h1>My E-Commerce Dashboard</h1>
    <p>Welcome, <strong><?= e($_SESSION['user_name']) ?></strong></p>
</div>

<!-- ===== METRIC BOXES ===== -->
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:30px;">

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Total Users</h4>
        <p style="font-size:22px; font-weight:bold;">
            <?= number_format($totalUsers) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Total Products</h4>
        <p style="font-size:22px; font-weight:bold;">
            <?= number_format($totalProducts) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Total Orders</h4>
        <p style="font-size:22px; font-weight:bold;">
            <?= number_format($totalOrders) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Total Revenue</h4>
        <p style="font-size:22px; font-weight:bold;">
            $<?= number_format($totalRevenue,2) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Pending Orders</h4>
        <p style="font-size:22px; font-weight:bold; color:#d97706;">
            <?= number_format($pendingOrders) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>New Users (Last 7 Days)</h4>
        <p style="font-size:22px; font-weight:bold;">
            <?= number_format($newUsers7Days) ?>
        </p>
    </div>

    <div style="padding:20px; background:#f3f4f6; border-radius:8px;">
        <h4>Sales (Last 30 Days)</h4>
        <p style="font-size:22px; font-weight:bold;">
            $<?= number_format($sales30Days,2) ?>
        </p>
    </div>

</div>

<footer>
    © 2026 My E-Commerce
</footer>

</div>
</div>

</body>
</html>