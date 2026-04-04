<?php
require 'include/load.php';
checkLogin();

/* =========================
   METRICS (Real Data from Database)
========================= */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'Cancelled'")->fetchColumn();

// Fetch 5 Recent Orders
$recentOrdersStmt = $pdo->query("
    SELECT o.*, u.name as user_name, u.avatar 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC LIMIT 5
");
$recentOrders = $recentOrdersStmt->fetchAll();

$title = "Admin Dashboard";
$subTitle = "Analytics Overview";

include 'partials/layouts/layoutTop.php';
?>

<style>
    :root {
        --neon-blue: #00d2ff;
        --neon-purple: #9d50bb;
        --neon-green: #39ff14;
        --neon-orange: #ff6700;
    }

    .dashboard-main-body {
        padding: 30px 30px 30px 40px !important; 
        background: #fdfdfd;
        min-height: 100vh;
    }

    .dark .dashboard-main-body {
        background: #0b0f1a;
    }

    /* Fix for Export Data & Settings Button in Light Theme */
    .btn-export {
        background-color: #4f46e5 !important; /* Indigo background always */
        color: #ffffff !important; /* White text always for contrast */
    }

    .btn-settings {
        background-color: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    .dark .btn-settings {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }

    /* Verified Users Badge Text Fix */
    .verified-badge-text {
        color: #1e293b !important; 
        font-weight: 800;
    }

    .premium-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dark .premium-card {
        background: rgba(30, 41, 59, 0.5);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .icon-glow-blue { box-shadow: 0 0 15px rgba(0, 210, 255, 0.3); border: 1px solid var(--neon-blue); }
    .icon-glow-purple { box-shadow: 0 0 15px rgba(157, 80, 187, 0.3); border: 1px solid var(--neon-purple); }
    .icon-glow-orange { box-shadow: 0 0 15px rgba(255, 103, 0, 0.3); border: 1px solid var(--neon-orange); }
    .icon-glow-green { box-shadow: 0 0 15px rgba(57, 255, 20, 0.3); border: 1px solid var(--neon-green); }

    .btn-neon {
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
</style>

<div class="dashboard-main-body">
    <?php include 'partials/breadcrumb.php'; ?>

    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="pl-2">
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Dashboard <span class="text-indigo-600 dark:text-indigo-400">Core</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-2">
                <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                <?= date('l, d F Y') ?> | System is running smoothly.
            </p>
        </div>
        <div class="flex gap-3">
            <button class="btn-settings btn-neon px-5 py-3 rounded-2xl font-bold flex items-center justify-center shadow-sm">
                <iconify-icon icon="solar:settings-bold-duotone" class="text-2xl"></iconify-icon>
            </button>
            <button class="btn-export btn-neon px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                <iconify-icon icon="solar:cloud-download-bold-duotone" class="text-xl" style="color: white !important;"></iconify-icon>
                Export Data
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="premium-card p-6 relative overflow-hidden group">
            <div class="icon-box-premium icon-glow-blue bg-blue-50 dark:bg-blue-900/40 text-blue-500 w-12 h-12 flex items-center justify-center rounded-2xl mb-4">
                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Inventory Items</p>
            <h2 class="text-3xl font-black mt-2 text-slate-900 dark:text-white"><?= number_format($totalProducts) ?></h2>
            <div class="mt-4 flex items-center text-[10px] font-bold text-blue-500 bg-blue-50 dark:bg-blue-500/20 w-fit px-3 py-1 rounded-full">
                LIVE DATABASE
            </div>
        </div>

        <div class="premium-card p-6 relative overflow-hidden group">
            <div class="icon-box-premium icon-glow-purple bg-purple-50 dark:bg-purple-900/40 text-purple-500 w-12 h-12 flex items-center justify-center rounded-2xl mb-4">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-2xl" style="color: #9d50bb !important;"></iconify-icon>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Global Clients</p>
            <h2 class="text-3xl font-black mt-2 text-slate-900 dark:text-white"><?= number_format($totalUsers) ?></h2>
            <div class="mt-4 flex items-center bg-slate-100 dark:bg-slate-100 w-fit px-3 py-1 rounded-full shadow-sm">
                <span class="verified-badge-text text-[10px] uppercase tracking-wider">VERIFIED USERS</span>
            </div>
        </div>

        <div class="premium-card p-6 relative overflow-hidden group">
            <div class="icon-box-premium icon-glow-orange bg-orange-50 dark:bg-orange-900/40 text-orange-500 w-12 h-12 flex items-center justify-center rounded-2xl mb-4">
                <iconify-icon icon="solar:cart-large-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Bookings</p>
            <h2 class="text-3xl font-black mt-2 text-slate-900 dark:text-white"><?= number_format($totalOrders) ?></h2>
            <div class="mt-4 flex items-center text-[10px] font-bold text-orange-500 bg-orange-50 dark:bg-orange-500/20 w-fit px-3 py-1 rounded-full">
                RECENT SALES
            </div>
        </div>

        <div class="premium-card p-6 relative overflow-hidden group border-2 border-indigo-500/20">
            <div class="icon-box-premium icon-glow-green bg-green-50 dark:bg-green-900/40 text-green-500 w-12 h-12 flex items-center justify-center rounded-2xl mb-4">
                <iconify-icon icon="solar:wad-of-money-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Net Revenue</p>
            <h2 class="text-3xl font-black mt-2 text-green-600 dark:text-green-400">$<?= number_format($totalRevenue, 2) ?></h2>
            <div class="mt-4 flex items-center text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-500/20 w-fit px-3 py-1 rounded-full">
                SECURE PROFITS
            </div>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="p-8 pl-10 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black dark:text-white">Transaction Logs</h3>
                <p class="text-sm text-slate-500 mt-1">Detailed view of the latest movements.</p>
            </div>
            <a href="/ecommerce-theme-learning/invoices/dashboard.php" class="text-indigo-600 dark:text-indigo-400 font-bold text-sm flex items-center gap-1 hover:underline">
                View All Transactions <iconify-icon icon="lucide:arrow-right"></iconify-icon>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30">
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Client Identity</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Invoice ID</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider text-center">Lifecycle</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php foreach($recentOrders as $order): 
                        $customerName = !empty($order['user_name']) ? e($order['user_name']) : 'Guest User';
                        $avatarPath = (!empty($order['avatar'])) ? "assets/uploads/" . e($order['avatar']) : "https://ui-avatars.com/api/?name=" . urlencode($customerName) . "&background=6366f1&color=fff";
                    ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <img src="<?= $avatarPath ?>" alt="" class="w-11 h-11 rounded-2xl object-cover ring-2 ring-white dark:ring-slate-700 shadow-lg">
                                <span class="text-sm font-bold dark:text-slate-200 group-hover:text-indigo-600 transition-colors"><?= $customerName ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-mono text-slate-500 dark:text-slate-400">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black border uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border-emerald-500/20">
                                <?= $order['status'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-base font-black dark:text-white text-right">$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/layouts/layoutBottom.php'; ?>