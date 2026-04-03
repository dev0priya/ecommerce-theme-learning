<?php
require '../../include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('../../dashboard.php');
}

$userId = $_SESSION['user_id'];

/* USER DATA */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

/* STATS CALCULATIONS */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$userId]);
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status='Pending'");
$stmt->execute([$userId]);
$pendingOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(order_items.price * order_items.quantity),0)
    FROM orders
    JOIN order_items ON order_items.order_id = orders.id
    WHERE orders.user_id = ?");
$stmt->execute([$userId]);
$totalSpent = $stmt->fetchColumn();

include '../../partials/head.php'; 
?>

<?php include '../../partials/header.php'; ?>

<style>
    /* 1. Global Layout */
    .user-panel-wrapper {
        display: flex;
        min-height: 100vh;
        background: #fdfdfd;
    }
    .dark .user-panel-wrapper { background: #020617; }

    .main-content-area {
        flex: 1;
        margin-left: 280px; 
        display: flex;
        flex-direction: column;
        padding: 0 !important;
    }

    .content-body {
        padding: 40px;
        flex-grow: 1;
    }

    /* 2. Adjusted Typography */
    .welcome-text {
        font-size: 24px; /* Heading size reduced as requested */
        font-weight: 900;
        letter-spacing: -1px;
    }
    .sub-badge {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 3px;
        color: #6366f1;
    }

    /* 3. Neon Glass Cards */
    .neon-glass-card {
        background: white;
        border-radius: 24px;
        padding: 25px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: 0.3s;
    }
    .dark .neon-glass-card { 
        background: rgba(15, 23, 42, 0.5); 
        border-color: rgba(99, 102, 241, 0.2); 
    }
    .neon-glass-card:hover { transform: translateY(-5px); border-color: #6366f1; }

    /* 4. Feature Modules */
    .quick-link-btn {
        padding: 12px;
        border-radius: 14px;
        background: #f1f5f9;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s;
    }
    .dark .quick-link-btn { background: #0f172a; color: #f1f5f9; }
    .quick-link-btn:hover { background: #6366f1; color: white !important; }

    /* 5. Spacing for Explore Button */
    .btn-explore-container {
        margin-top: 50px; /* Spacing increased */
    }

    .btn-neon-v2 {
        background: #0f172a;
        color: white !important;
        padding: 16px 35px;
        border-radius: 16px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.5px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .dark .btn-neon-v2 { background: #6366f1; }

    .footer-no-gap {
        width: 100%;
        margin-top: auto;
        border-top: 1px solid rgba(226, 232, 240, 0.8);
    }

    @media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    
    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        
        <div class="content-body">
            <div class="mb-10">
                <span class="sub-badge">Account Overview</span>
                <h1 class="welcome-text text-slate-900 dark:text-white mt-1">
                    Hello, <span class="text-indigo-600"><?= e($user['name']) ?></span>
                </h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="neon-glass-card border-b-4 border-b-cyan-500">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Orders</span>
                    <span class="text-3xl font-black text-slate-900 dark:text-white block mt-2"><?= $pendingOrders ?></span>
                </div>
                <div class="neon-glass-card border-b-4 border-b-indigo-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Wallet Spent</span>
                    <span class="text-3xl font-black text-slate-900 dark:text-white block mt-2">₹<?= number_format($totalSpent, 2) ?></span>
                </div>
                <div class="neon-glass-card border-b-4 border-b-purple-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Completed</span>
                    <span class="text-3xl font-black text-slate-900 dark:text-white block mt-2"><?= $totalOrders ?></span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="neon-glass-card">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="../../edit-profile.php" class="quick-link-btn">
                            <iconify-icon icon="solar:user-bold-duotone" class="text-lg"></iconify-icon> Edit Profile
                        </a>
                        <a href="../../wishlist.php" class="quick-link-btn">
                            <iconify-icon icon="solar:heart-bold-duotone" class="text-lg text-rose-500"></iconify-icon> Wishlist
                        </a>
                        <a href="../../cart/index.php" class="quick-link-btn">
                            <iconify-icon icon="solar:cart-bold-duotone" class="text-lg text-emerald-500"></iconify-icon> My Cart
                        </a>
                        <a href="../../contact.php" class="quick-link-btn">
                            <iconify-icon icon="solar:help-bold-duotone" class="text-lg text-indigo-500"></iconify-icon> Support
                        </a>
                    </div>
                </div>

                <div class="neon-glass-card flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-2">Account Security</h3>
                        <p class="text-[10px] font-bold text-emerald-500 uppercase flex items-center gap-2">
                            <iconify-icon icon="solar:shield-check-bold"></iconify-icon> Your account is secure
                        </p>
                    </div>
                    <iconify-icon icon="solar:shield-user-bold-duotone" class="text-6xl text-slate-100 dark:text-slate-800"></iconify-icon>
                </div>

            </div>

            <div class="btn-explore-container">
                <a href="orders.php" class="btn-neon-v2">
                    Explore My Orders 
                    <iconify-icon icon="solar:round-alt-arrow-right-bold" class="text-lg"></iconify-icon>
                </a>
            </div>
        </div>

        <div class="footer-no-gap">
            <?php include '../../partials/footer.php'; ?>
        </div>

    </div>
</div>

</body>
</html>