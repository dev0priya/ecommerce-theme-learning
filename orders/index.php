<?php
require '../include/load.php';
checkLogin();

$title = 'Order Management';
$subTitle = 'E-Commerce / Sales';

// Fetch orders with customer name (JOIN)
$sql = "SELECT orders.*, users.name AS customer_name 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.created_at DESC";

$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll();

include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Full Width & Spacing Fix */
    .order-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        width: 100%;
    }
    .dark .order-card { background: #0f172a; border-color: #1e293b; }

    /* --- REVENUE BOX STYLING (FIXED FOR DARK THEME) --- */
    .revenue-stat-box {
        background: #ffffff; /* Hamesha Light Background */
        padding: 12px 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        text-align: center;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    /* Jab theme Black ho, tab Box ke andar ka text Black hona chahiye */
    .dark .revenue-stat-box .stat-label {
        color: #475569 !important; /* Dark Gray text in Dark Theme */
    }
    .dark .revenue-stat-box .stat-amount {
        color: #1e293b !important; /* Deep Blackish text in Dark Theme */
    }

    .stat-label {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        color: #64748b;
        tracking-widest: 1px;
        margin-bottom: 2px;
    }
    .stat-amount {
        font-size: 1.4rem;
        font-weight: 900;
        color: #4f46e5; /* Indigo color for amount */
    }

    /* Professional Table Setup */
    .premium-table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: auto; 
    }

    /* Column Widths */
    .col-id { width: 80px; }
    .col-customer { width: auto; min-width: 220px; } 
    .col-date { width: 150px; }
    .col-total { width: 120px; }
    .col-status { width: 130px; text-align: center; }
    .col-action { width: 80px; text-align: right; }

    .premium-table th {
        padding: 18px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .dark .premium-table th { border-bottom-color: #1e293b; color: #94a3b8; }
    
    .premium-table td {
        padding: 18px 24px;
        font-size: 14px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .dark .premium-table td { color: #f1f5f9; border-bottom-color: #1e293b; }

    /* Action Icon Button */
    .btn-view-pro {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f1f5f9;
        color: #0f172a;
        transition: 0.3s ease;
    }
    .dark .btn-view-pro { background: #1e293b; color: #ffffff; }
    .btn-view-pro:hover { background: #6366f1; color: white !important; transform: scale(1.1); }

    .status-pill {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }
</style>

<div class="dashboard-main-body px-6 py-10">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Order Logs</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">Full transaction history and merchant records.</p>
            </div>
            
            <div class="revenue-stat-box">
                <span class="stat-label">Total Revenue</span>
                <span class="stat-amount">$<?= number_format(array_sum(array_column($orders, 'total_amount')), 2) ?></span>
            </div>
        </div>

        <div class="order-card">
            <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center bg-slate-50/30 dark:bg-slate-900/40">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:bill-list-bold-duotone" class="text-xl text-slate-900 dark:text-white"></iconify-icon>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-0">Active Transactions</h3>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter"><?= count($orders) ?> Results Found</span>
            </div>

            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th class="col-id">Order ID</th>
                            <th class="col-customer">Customer</th>
                            <th class="col-date">Date</th>
                            <th class="col-total">Total</th>
                            <th class="col-status">Status</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                            <td class="font-mono text-xs font-bold text-slate-400">#<?= e($order['id']) ?></td>
                            <td class="font-bold text-slate-900 dark:text-white"><?= e($order['customer_name']) ?></td>
                            <td class="text-slate-500 dark:text-slate-400 text-sm font-medium"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td class="font-black text-slate-900 dark:text-white">$<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="text-center">
                                <?php 
                                    $status = strtolower($order['status']);
                                    $bg = ($status == 'pending') ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)';
                                    $color = ($status == 'pending') ? '#f59e0b' : '#10b981';
                                ?>
                                <span class="status-pill" style="background: <?= $bg ?>; color: <?= $color ?>;">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="view.php?id=<?= $order['id'] ?>" class="btn-view-pro" title="View Transaction">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="text-xl"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>