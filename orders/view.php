<?php
require '../include/load.php';
checkLogin();

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header('Location: index.php');
    exit;
}

// 1. Fetch Order + Customer Details
$sql = "SELECT o.*, u.name as customer_name, u.email as customer_email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// 2. Fetch Order Items (Values empty na rahein isliye columns confirm kiye hain)
// Note: Make sure 'order_items' table has 'product_id', 'quantity', and 'price' columns.
$sqlItems = "SELECT oi.*, p.product_name
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?";
$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll();

$title = "Order Details #" . $order_id;
include '../partials/head.php';
?>

<style>
    :root {
        --brand: #6366f1;
        --brand-light: #eef2ff;
        --success: #10b981;
        --warning: #f59e0b;
        --slate-900: #0f172a;
        --slate-500: #64748b;
        --bg-main: #f8fafc;
    }

    body { background: var(--bg-main); font-family: 'Inter', sans-serif; padding: 40px 0; margin: 0; }
    .container { max-width: 1000px; margin: 0 auto; padding: 0 20px; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: var(--slate-500);
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    .btn-back:hover { color: var(--brand); border-color: var(--brand); }

    .main-card {
        background: white;
        border-radius: 2rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .card-header {
        padding: 30px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-pending { background: #fffbeb; color: var(--warning); }
    .status-paid { background: #ecfdf5; color: var(--success); }

    .items-table { width: 100%; border-collapse: collapse; }
    .items-table th { 
        text-align: left; 
        padding: 15px 30px; 
        background: #f8fafc; 
        color: var(--slate-500); 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        letter-spacing: 1px;
    }
    .items-table td { 
        padding: 20px 30px; 
        border-bottom: 1px solid #f8fafc; 
        font-weight: 600;
        color: var(--slate-900);
    }
    
    /* Summary Row Styling */
    .total-row { background: #fcfdff; }
    .total-label { text-align: right; padding-right: 50px; color: var(--slate-500); }
    .total-value { font-size: 1.5rem; color: var(--brand); font-weight: 900; }
</style>

<div class="container">
    <a href="index.php" class="btn-back">
        <iconify-icon icon="solar:alt-arrow-left-bold-duotone"></iconify-icon>
        Back to Order List
    </a>

    <div class="main-card">
        <div class="card-header">
            <div>
                <h1 style="margin:0; font-weight:900; font-size: 1.8rem;">Invoice #<?= e($order['id']) ?></h1>
                <p style="margin:5px 0 0; color:var(--slate-500);">Customer: <b><?= e($order['customer_name'] ?? 'Walk-in') ?></b></p>
            </div>
            <span class="status-badge <?= strtolower($order['status']) == 'pending' ? 'status-pending' : 'status-paid' ?>">
                <?= e($order['status']) ?>
            </span>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th style="text-align: center;">Qty</th>
                    <th>Unit Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                foreach ($items as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td style="color: var(--brand);">
                        <iconify-icon icon="solar:box-bold-duotone" style="margin-right:8px; vertical-align:middle;"></iconify-icon>
                        <?= e($item['product_name']) ?>
                    </td>
                    <td style="text-align: center;"><?= e($item['quantity']) ?></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td style="text-align: right; font-weight: 800;">₹<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="total-label">Grand Total</td>
                    <td style="text-align: right;" class="total-value">₹<?= number_format($grand_total, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="main-card" style="margin-top: 30px; padding: 30px;">
        <h3 style="margin-top:0; font-weight: 800;">Update Status</h3>
        <form action="update_status.php" method="POST" style="display:flex; gap:15px;">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" style="flex:1; padding:12px; border-radius:12px; border:1px solid #e2e8f0; font-weight:600;">
                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= $order['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
            </select>
            <button type="submit" style="background:var(--brand); color:white; border:none; padding:12px 25px; border-radius:12px; font-weight:700; cursor:pointer;">
                Update Order
            </button>
        </form>
    </div>
</div>

</body>
</html>