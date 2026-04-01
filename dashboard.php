<?php
require 'include/load.php';
checkLogin();

/* =========================
   METRICS
========================= */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(price * quantity),0) FROM order_items")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$sales30Days = $pdo->query("SELECT COALESCE(SUM(order_items.price * order_items.quantity),0) FROM orders JOIN order_items ON order_items.order_id = orders.id WHERE orders.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

$title = "Dashboard";
$subTitle = "Overview";

include 'partials/layouts/layoutTop.php';
?>

<div class="dashboard-main-body">

    <?php include 'partials/breadcrumb.php'; ?>

    <!-- 🔥 HEADER -->
    <div class="px-6 mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
            Welcome back 👋
        </h1>
        <p class="text-neutral-500 mt-1">
            <?= e($_SESSION['user_name']) ?> — here's what's happening today.
        </p>
    </div>

    <!-- 🔥 STATS GRID -->
    <div class="px-6 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- CARD -->
            <div class="bg-white dark:bg-neutral-900 p-5 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-lg transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-neutral-400">Total Users</p>
                        <h3 class="text-2xl font-bold mt-2 dark:text-white"><?= number_format($totalUsers) ?></h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <iconify-icon icon="mdi:account-group-outline" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-900 p-5 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-lg transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-neutral-400">Products</p>
                        <h3 class="text-2xl font-bold mt-2 dark:text-white"><?= number_format($totalProducts) ?></h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                        <iconify-icon icon="mdi:shopping-outline" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-900 p-5 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-lg transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-neutral-400">Orders</p>
                        <h3 class="text-2xl font-bold mt-2 dark:text-white"><?= number_format($totalOrders) ?></h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <iconify-icon icon="mdi:cart-outline" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-900 p-5 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-lg transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-neutral-400">Revenue</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-2">$<?= number_format($totalRevenue, 2) ?></h3>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <iconify-icon icon="mdi:currency-usd" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 🔥 SECOND ROW -->
    <div class="px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- PENDING -->
            <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <h5 class="font-semibold mb-4 dark:text-white">Pending Orders</h5>
                <p class="text-3xl font-bold text-yellow-500"><?= number_format($pendingOrders) ?></p>
            </div>

            <!-- SALES -->
            <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                <h5 class="font-semibold mb-4 dark:text-white">Last 30 Days Sales</h5>
                <p class="text-3xl font-bold text-primary-600">$<?= number_format($sales30Days, 2) ?></p>
            </div>

        </div>
    </div>

</div>

<?php include 'partials/layouts/layoutBottom.php'; ?>