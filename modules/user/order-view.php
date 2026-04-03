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

/* FETCH ORDER DETAILS */
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    redirect('dashboard.php');
}

/* FETCH ORDER ITEMS */
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

<style>
    /* 1. Global Reset & Neon Background */
    .user-panel-wrapper {
        display: flex;
        min-height: 100vh;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }
    .dark .user-panel-wrapper { background: #020617; }

    /* Neon Aura Blobs */
    .neon-aura {
        position: absolute;
        width: 500px;
        height: 500px;
        filter: blur(140px);
        border-radius: 50%;
        z-index: 0;
        opacity: 0.1;
        pointer-events: none;
    }
    .aura-indigo { top: -10%; right: -5%; background: #6366f1; }
    .aura-cyan { bottom: -10%; left: -5%; background: #06b6d4; }

    .main-content-area {
        flex: 1;
        margin-left: 280px; 
        display: flex;
        flex-direction: column;
        z-index: 1;
        padding: 0 !important; 
    }

    .content-body { padding: 50px 40px; flex-grow: 1; }

    /* 2. Cyber Tracker Design */
    .status-tracker-neon {
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
        padding: 30px;
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .dark .status-tracker-neon { background: rgba(15, 23, 42, 0.4); border-color: rgba(99, 102, 241, 0.2); backdrop-filter: blur(10px); }
    
    .status-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    .step-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: 0.4s;
    }
    .dark .step-icon { background: #1e293b; }
    
    /* Active State */
    .status-active .step-icon {
        background: #6366f1;
        color: white;
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
    }
    .status-active .step-label { color: #6366f1; font-weight: 950; }

    .step-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; }

    /* 3. Invoice Glass Card */
    .invoice-card-premium {
        background: #ffffff;
        border-radius: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 50px rgba(0,0,0,0.02);
    }
    .dark .invoice-card-premium { background: rgba(15, 23, 42, 0.6); border-color: rgba(255,255,255,0.05); }

    .table-neon th {
        padding: 25px;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #1e293b; /* White theme Visibility Fix */
        border-bottom: 2px solid #f1f5f9;
    }
    .dark .table-neon th { color: #94a3b8; border-color: #1e293b; }
    
    .table-neon td { padding: 25px; font-weight: 700; color: #1e293b; }
    .dark .table-neon td { color: #f1f5f9; }

    /* 4. Bill Summary Glow */
    .summary-box-neon {
        background: #f8fafc;
        padding: 35px;
        border-radius: 24px;
        border-left: 4px solid #6366f1;
    }
    .dark .summary-box-neon { background: rgba(2, 6, 23, 0.4); border-left-color: #6366f1; }

    /* 5. Cancel Button */
    .btn-cancel-cyber {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e !important;
        border: 1px solid rgba(244, 63, 94, 0.3);
        padding: 16px 32px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 2px;
        transition: 0.3s;
    }
    .btn-cancel-cyber:hover {
        background: #f43f5e;
        color: white !important;
        box-shadow: 0 0 25px rgba(244, 63, 94, 0.4);
    }

    .footer-no-gap { width: 100%; margin-top: auto; border-top: 1px solid #f1f5f9; background: #fff; }
    .dark .footer-no-gap { background: #020617; border-color: #1e293b; }

    @media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    <div class="neon-aura aura-indigo"></div>
    <div class="neon-aura aura-cyan"></div>

    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-12 flex items-center justify-between">
                <div>
                    <a href="orders.php" class="text-indigo-500 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 mb-3">
                        <iconify-icon icon="solar:double-alt-arrow-left-bold-duotone" class="text-lg"></iconify-icon> Back to Portal
                    </a>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter italic">
                        Order Manifest #<?= $order['id'] ?>
                    </h1>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Authenticated On</span>
                    <span class="font-bold text-slate-900 dark:text-white"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
                </div>
            </div>

            <div class="status-tracker-neon">
                <div class="status-step <?= ($order['status'] == 'Pending') ? 'status-active' : '' ?>">
                    <div class="step-icon"><iconify-icon icon="solar:clock-circle-bold-duotone" class="text-2xl"></iconify-icon></div>
                    <span class="step-label">Pending</span>
                </div>
                <div class="status-step <?= ($order['status'] == 'Processing') ? 'status-active' : '' ?>">
                    <div class="step-icon"><iconify-icon icon="solar:settings-minimalistic-bold-duotone" class="text-2xl"></iconify-icon></div>
                    <span class="step-label">Processing</span>
                </div>
                <div class="status-step <?= ($order['status'] == 'Completed') ? 'status-active' : '' ?>">
                    <div class="step-icon"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-2xl"></iconify-icon></div>
                    <span class="step-label">Delivered</span>
                </div>
            </div>

            <div class="invoice-card-premium">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Asset Inventory</h3>
                    <span class="bg-indigo-600 text-white px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-lg shadow-indigo-500/30">
                        <?= e($order['status']) ?>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-neon w-full">
                        <thead>
                            <tr>
                                <th>Item Specification</th>
                                <th>Rate</th>
                                <th>Quantity</th>
                                <th class="text-right">Valuation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grandTotal = 0;
                            foreach($items as $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $grandTotal += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="font-black text-slate-900 dark:text-white"><?= e($item['product_name']) ?></div>
                                </td>
                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                <td><span class="opacity-50">×</span> <?= $item['quantity'] ?></td>
                                <td class="text-right font-black text-indigo-600 dark:text-indigo-400">₹<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-end">
                    <div>
                        <?php if ($order['status'] === 'Pending'): ?>
                        <form method="POST" action="cancel-order.php">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn-cancel-cyber">
                                <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone" class="text-xl"></iconify-icon> 
                                Terminate Order
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <div class="summary-box-neon shadow-sm">
                        <div class="flex justify-between mb-4">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Subtotal</span>
                            <span class="font-black text-slate-700 dark:text-slate-300">₹<?= number_format($grandTotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between mb-6">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Logistic Fee</span>
                            <span class="font-black text-emerald-500 uppercase text-[10px] tracking-tighter">Complimentary Shipping</span>
                        </div>
                        <div class="flex justify-between pt-6 border-t border-slate-200 dark:border-slate-800">
                            <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-[3px]">Total Value</span>
                            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400 drop-shadow-md">₹<?= number_format($grandTotal, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-no-gap">
            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>