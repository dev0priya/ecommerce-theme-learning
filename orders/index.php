<?php
require '../include/load.php';
checkLogin();

// Customer name fetch karne ke liye JOIN query
$sql = "SELECT o.*, u.name AS customer_name 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";

$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll();

$title = "Order Management";
include '../partials/head.php'; 
?>

<style>
    :root {
        --brand: #6366f1; 
        --brand-light: #eef2ff;
        --success: #10b981; 
        --warning: #f59e0b; 
        --slate-800: #1e293b;
        --slate-500: #64748b;
        --bg-main: #f8fafc;
    }

    body { margin: 0; padding: 0; background: var(--bg-main); font-family: 'Inter', sans-serif; overflow: hidden; }

    /* Layout: Sidebar ke baad bachi hui width content area lega */
    .app-container {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    .sidebar-wrapper {
        width: 260px;
        flex-shrink: 0;
        background: #fff;
        border-right: 1px solid #e2e8f0;
    }

    .main-wrapper {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        min-width: 0; 
    }

    .header-section {
        background: white;
        padding: 2rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Search Bar Styling */
    .search-container {
        margin-top: 1rem;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 3rem;
        background: var(--bg-main);
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        outline: none;
        transition: 0.3s;
    }

    .search-input:focus {
        border-color: var(--brand);
        background: white;
        box-shadow: 0 0 0 4px var(--brand-light);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--slate-500);
    }

    /* Table & Action Button */
    .content-area {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .modern-table th {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        color: var(--slate-500);
        text-transform: uppercase;
        font-weight: 800;
    }

    .modern-table td {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f8fafc;
    }

    /* Action View Button */
    .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--brand-light);
        color: var(--brand);
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none;
        transition: 0.2s;
    }

    .view-btn:hover {
        background: var(--brand);
        color: white;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 800;
    }
    .status-pending { background: #fffbeb; color: var(--warning); }
    .status-paid { background: #ecfdf5; color: var(--success); }
</style>

<div class="app-container">
    <aside class="sidebar-wrapper">
        <?php
        // Sidebar logic
        if ($_SESSION['user_role'] === 'admin') {
            include $_SERVER['DOCUMENT_ROOT'] . '/ecommerce-theme-learning/partials/sidebar-admin.php';
        } else {
            include $_SERVER['DOCUMENT_ROOT'] . '/ecommerce-theme-learning/partials/sidebar-user.php';
        }
        ?>
    </aside>

    <main class="main-wrapper">
        <header class="header-section">
            <h1 style="font-size: 1.8rem; font-weight: 900; color: var(--slate-800); margin: 0;">Orders Overview</h1>
            <p style="color: var(--slate-500); margin-top: 0.2rem;">Managing all incoming customer transactions</p>
            
            <div class="search-container">
                <iconify-icon icon="solar:magnifer-linear" class="search-icon"></iconify-icon>
                <input type="text" placeholder="Search by Order ID or Customer Name..." class="search-input">
            </div>
        </header>

        <div class="content-area">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="font-weight: 800; color: var(--brand);">#<?= e($order['id']) ?></td>
                        <td style="font-weight: 600;"><?= e($order['customer_name'] ?? 'Guest User') ?></td>
                        <td style="color: var(--slate-500);"><?= date('d M, Y', strtotime($order['created_at'])) ?></td>
                        <td style="font-weight: 800;">₹<?= number_format($order['total_amount'], 2) ?></td>
                        <td style="text-align: center;">
                            <?php $st = strtolower($order['status']); ?>
                            <span class="status-badge <?= ($st == 'pending') ? 'status-pending' : 'status-paid' ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="view.php?id=<?= $order['id'] ?>" class="view-btn">
                                <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                View Order
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>