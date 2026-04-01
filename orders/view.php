<?php
require '../include/load.php';
checkLogin();

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header('Location: index.php');
    exit();
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
    header('Location: index.php');
    exit();
}

// 2. Fetch order items + product names
$sqlItems = "SELECT order_items.*, products.product_name, products.image
             FROM order_items
             JOIN products ON order_items.product_id = products.id
             WHERE order_items.order_id = ?";

$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll();

$title = 'Order Details';
$subTitle = 'E-Commerce / View Order';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Premium Containers */
    .view-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .dark .view-card {
        background: #111827;
        border-color: #1f2937;
    }

    /* Order Header Labels */
    .detail-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1px;
        margin-bottom: 4px;
        display: block;
    }

    /* Product Row Styling */
    .item-row {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 0;
    }
    .dark .item-row { border-bottom-color: #1f2937; }
    .item-row:last-child { border: none; }

    /* Status Pills Adaptive */
    .status-badge-big {
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Price Summary Box */
    .summary-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
    }
    .dark .summary-box { background: #0f172a; }

    /* Form Styling */
    .pro-select {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        outline: none;
    }
    .dark .pro-select { background: #1f2937; border-color: #334151; color: white; }

    .btn-update-status {
        background: #4f46e5;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn-update-status:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
</style>

<div class="dashboard-main-body px-6 py-10">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <a href="index.php" class="flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold transition-all">
                <iconify-icon icon="solar:alt-arrow-left-bold" class="text-xl"></iconify-icon>
                <span>Back to List</span>
            </a>
            <div class="flex items-center gap-4">
                <button onclick="window.print()" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <iconify-icon icon="solar:printer-bold" class="text-xl"></iconify-icon>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-8">
                
                <div class="view-card shadow-sm">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div>
                            <span class="detail-label">Transaction Reference</span>
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white">Order #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h2>
                            <p class="text-sm text-slate-400 font-medium mt-1">Placed on <?= date('d M, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
                        </div>
                        <div class="flex items-start">
                            <?php 
                                $status = $order['status'];
                                $color = ($status == 'Pending') ? '#f59e0b' : (($status == 'Canceled') ? '#ef4444' : '#10b981');
                                $bg = $color . '15'; // 15% opacity
                            ?>
                            <div class="status-badge-big" style="background: <?= $bg ?>; color: <?= $color ?>;">
                                <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                                <?= strtoupper($status) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="view-card shadow-sm">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-8">Ordered Items</h3>
                    <div class="space-y-4">
                        <?php foreach ($items as $item): ?>
                        <div class="item-row flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border border-slate-100 dark:border-slate-800">
                                    <?php if(!empty($item['image'])): ?>
                                        <img src="../assets/uploads/<?= $item['image'] ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <iconify-icon icon="solar:box-bold-duotone" class="text-3xl text-slate-300"></iconify-icon>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white"><?= e($item['product_name']) ?></h4>
                                    <span class="text-xs text-slate-400 font-medium">Unit Price: $<?= number_format($item['price'], 2) ?></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-black text-slate-900 dark:text-white">x<?= $item['quantity'] ?></span>
                                <span class="block text-sm font-bold text-indigo-600">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="view-card shadow-sm border-dashed border-2 border-indigo-100 dark:border-indigo-900/30">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white mb-6 uppercase">Order Fulfillment</h3>
                    <form action="../api/orders/update.php" method="POST" class="flex flex-col sm:flex-row gap-4">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status" class="pro-select flex-grow">
                            <option value="Pending"   <?= $order['status'] === 'Pending'   ? 'selected' : '' ?>>Pending</option>
                            <option value="Shipped"   <?= $order['status'] === 'Shipped'   ? 'selected' : '' ?>>Shipped</option>
                            <option value="Delivered" <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="Canceled"  <?= $order['status'] === 'Canceled'  ? 'selected' : '' ?>>Canceled</option>
                        </select>
                        <button type="submit" class="btn-update-status flex items-center justify-center gap-2">
                            <iconify-icon icon="solar:refresh-bold" class="text-lg"></iconify-icon>
                            Update Milestone
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                
                <div class="view-card shadow-sm">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6">Customer Profile</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white font-black">
                            <?= strtoupper(substr($order['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 dark:text-white leading-tight"><?= e($order['name']) ?></h4>
                            <p class="text-xs text-slate-400"><?= e($order['email']) ?></p>
                        </div>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:map-point-bold" class="text-slate-300 text-lg"></iconify-icon>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium italic">No shipping address provided.</span>
                        </div>
                    </div>
                </div>

                <div class="view-card shadow-sm !p-0 overflow-hidden">
                    <div class="p-6 bg-slate-900 text-white">
                        <h3 class="text-xs font-black uppercase tracking-widest opacity-60 mb-2">Total Amount</h3>
                        <div class="text-4xl font-black">$<?= number_format($order['total_amount'], 2) ?></div>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase">Subtotal</span>
                            <span class="text-slate-900 dark:text-white font-black">$<?= number_format($order['total_amount'], 2) ?></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400 font-bold uppercase">Tax (0%)</span>
                            <span class="text-slate-900 dark:text-white font-black">$0.00</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-between">
                            <span class="text-xs font-black text-indigo-600 uppercase">Paid in Full</span>
                            <iconify-icon icon="solar:shield-check-bold" class="text-indigo-600 text-lg"></iconify-icon>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>