<?php
require 'include/load.php';
checkLogin();

/* =========================
   DASHBOARD METRICS LOGIC 
   (Keeping your original backend logic intact)
========================= */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(price * quantity),0) FROM order_items")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$newUsers7Days = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
$sales30Days = $pdo->query("SELECT COALESCE(SUM(order_items.price * order_items.quantity),0) FROM orders JOIN order_items ON order_items.order_id = orders.id WHERE orders.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

// 1. HEADER: Loads HTML, Head, and opening Body tags
include 'partials/header.php'; 
?>

<div class="flex min-h-screen">

    <?php
    if ($_SESSION['user_role'] === 'admin') {
        include 'partials/sidebar-admin.php';
    } else {
        include 'partials/sidebar-user.php';
    }
    ?>

    <main class="dashboard-main flex-grow-1">
        
        <div class="dashboard-main-body p-6">
            
            <div class="page-header mb-6">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Dashboard Overview</h1>
                <p class="text-neutral-500">Welcome back, <span class="text-primary-600 font-semibold"><?= e($_SESSION['user_name']) ?></span></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Total Users</h4>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2"><?= number_format($totalUsers) ?></p>
                </div>

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Total Products</h4>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2"><?= number_format($totalProducts) ?></p>
                </div>

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Total Orders</h4>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2"><?= number_format($totalOrders) ?></p>
                </div>

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Total Revenue</h4>
                    <p class="text-3xl font-bold text-success-600 mt-2">$<?= number_format($totalRevenue, 2) ?></p>
                </div>

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Pending Orders</h4>
                    <p class="text-3xl font-bold text-warning-600 mt-2"><?= number_format($pendingOrders) ?></p>
                </div>

                <div class="bg-white dark:bg-neutral-700 p-6 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-600">
                    <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">Last 30 Days Sales</h4>
                    <p class="text-3xl font-bold text-primary-600 mt-2">$<?= number_format($sales30Days, 2) ?></p>
                </div>

            </div>
            </div> <?php include 'partials/footer.php'; ?>

    </main> </div>