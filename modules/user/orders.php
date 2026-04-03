<?php
require '../../include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('../../dashboard.php');
}

$userId = $_SESSION['user_id'];

/* Fetch Orders from ecommerce_db */
$stmt = $pdo->prepare("SELECT id, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

include '../../partials/head.php'; 
?>

<?php include '../../partials/header.php'; ?>

<style>
    /* 1. Layout Fixes (No Horizontal Scroll) */
    html, body { max-width: 100%; overflow-x: hidden; margin: 0; padding: 0; }

    .user-panel-wrapper { display: flex; min-height: 100vh; background: #f8fafc; position: relative; }
    .dark .user-panel-wrapper { background: #020617; }

    .main-content-area { 
        flex: 1; 
        margin-left: 280px; 
        width: calc(100% - 280px); 
        display: flex; 
        flex-direction: column; 
        z-index: 1; 
    }

    .content-body { padding: 40px; flex-grow: 1; }

    /* 2. Heading Visibility Fix */
    .page-title { font-size: 28px; font-weight: 950; color: #1e293b; letter-spacing: -1.5px; }
    .dark .page-title { color: #ffffff !important; text-shadow: 0 0 15px rgba(255,255,255,0.1); }
    
    .section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 4px; }
    .dark .section-label { color: #94a3b8 !important; }

    /* 3. New Table Look (Floating Row Style) */
    .table-responsive-container { width: 100%; overflow-x: auto; border-radius: 24px; }
    
    .neon-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; min-width: 800px; }
    
    .neon-table th { 
        font-size: 10px; 
        font-weight: 950; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        color: #64748b; 
        padding: 0 25px 10px 25px;
        text-align: left;
    }
    .dark .neon-table th { color: #475569; }

    .neon-row td { 
        padding: 22px 25px; 
        background: white; 
        font-weight: 700; 
        color: #1e293b;
        transition: 0.3s;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .neon-row td { 
        background: rgba(15, 23, 42, 0.6); 
        color: #f1f5f9; 
        border-color: rgba(99, 102, 241, 0.1); 
    }
    
    .neon-row td:first-child { border-left: 1px solid #f1f5f9; border-radius: 18px 0 0 18px; }
    .neon-row td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 18px 18px 0; }
    .dark .neon-row td:first-child, .dark .neon-row td:last-child { border-color: rgba(99, 102, 241, 0.1); }

    .neon-row:hover td { transform: translateY(-3px); border-color: #6366f1; box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
    .dark .neon-row:hover td { background: rgba(15, 23, 42, 0.9); }

    /* 4. Neon Buttons */
    .btn-neon-cyan {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4 !important;
        border: 1px solid rgba(6, 182, 212, 0.3);
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .dark .btn-neon-cyan { background: rgba(6, 182, 212, 0.15); color: #22d3ee !important; }
    .btn-neon-cyan:hover { background: #06b6d4; color: white !important; box-shadow: 0 0 20px rgba(6, 182, 212, 0.4); }

    /* 5. Status Badges */
    .status-pill { font-size: 9px; font-weight: 900; padding: 6px 12px; border-radius: 8px; text-transform: uppercase; border: 1px solid transparent; }
    .status-completed { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2); }

    /* Footer Fix */
    .footer-no-gap { width: 100%; margin-top: auto; border-top: 1px solid #e2e8f0; background: white; }
    .dark .footer-no-gap { background: #020617; border-color: #1e293b; }

    @media (max-width: 991px) { .main-content-area { margin-left: 0 !important; width: 100%; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-12 flex items-center justify-between">
                <div>
                    <span class="section-label">Transaction Logs</span>
                    <h1 class="page-title mt-1 italic">Order History</h1>
                </div>
                <div class="hidden md:flex gap-4">
                    <div class="bg-white dark:bg-slate-900 px-6 py-3 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm text-center">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Active Orders</span>
                        <span class="text-xl font-black text-indigo-600"><?= count($orders) ?></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive-container">
                <table class="neon-table">
                    <thead>
                        <tr>
                            <th>Identity</th>
                            <th>Date Ref</th>
                            <th>Payment Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr class="neon-row">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center">
                                        <iconify-icon icon="solar:box-bold-duotone" class="text-indigo-500"></iconify-icon>
                                    </div>
                                    <span class="font-black text-indigo-600 dark:text-indigo-400">#<?= $order['id'] ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="text-xs font-bold opacity-60 italic"><?= date('d M, Y', strtotime($order['created_at'])) ?></span>
                            </td>
                            <td>
                                <?php $sClass = ($order['status'] == 'Completed') ? 'status-completed' : 'status-pending'; ?>
                                <span class="status-pill <?= $sClass ?>"><?= e($order['status']) ?></span>
                            </td>
                            <td class="text-right">
                                <a href="order-view.php?id=<?= $order['id'] ?>" class="btn-neon-cyan shadow-sm">
                                    <iconify-icon icon="solar:document-text-bold-duotone" class="text-lg"></iconify-icon>
                                    <span>Details</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if(empty($orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-24">
                                <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">Database record is empty</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-no-gap">
            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>