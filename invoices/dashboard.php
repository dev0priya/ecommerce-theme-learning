<?php 
require '../include/load.php'; 
checkLogin(); 

// 1. Stats Queries
$rev_query = $pdo->query("SELECT SUM(grand_total) as total FROM invoices WHERE status = 'paid'")->fetch();
$revenue = $rev_query['total'] ?? 0;

$pend_query = $pdo->query("SELECT SUM(grand_total) as total FROM invoices WHERE status = 'unpaid'")->fetch();
$pending = $pend_query['total'] ?? 0;

$count_query = $pdo->query("SELECT COUNT(id) as count FROM invoices")->fetch();
$inv_count = $count_query['count'] ?? 0;

// 2. Pagination Logic - CHANGED LIMIT TO 12
$limit = 12; 
$total_rows = $pdo->query("SELECT COUNT(id) FROM invoices")->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$title = 'Financial Dashboard';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Full Screen & Layout Fix */
    .dashboard-main-body { 
        background: #f8fafc; 
        min-height: 100vh; 
        display: flex;
        flex-direction: column;
        transition: 0.3s; 
    }
    .dark .dashboard-main-body { background: #020617; }

    .dashboard-content-wrapper {
        padding: 30px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    /* Stat Cards Styling */
    .stat-card-pop {
        background: white;
        padding: 25px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .dark .stat-card-pop { background: #0f172a; border-color: #1e293b; }
    .stat-card-pop:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

    .accent-revenue { border-top: 4px solid #10b981; }
    .accent-pending { border-top: 4px solid #f43f5e; }
    .accent-total { border-top: 4px solid #6366f1; }

    .icon-circle {
        width: 45px; height: 45px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 15px; font-size: 20px;
    }
    .bg-revenue { background: #d1fae5; color: #059669; }
    .bg-pending { background: #fee2e2; color: #dc2626; }
    .bg-total { background: #e0e7ff; color: #4f46e5; }

    /* Main Activity Container */
    .activity-container {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 25px;
        flex-grow: 1;
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    .dark .activity-container { background: #0f172a; border-color: #1e293b; }

    /* Table Styling */
    .premium-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .premium-table th {
        text-align: left; padding: 15px 20px; font-size: 11px;
        font-weight: 800; text-transform: uppercase; color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .premium-table th { border-bottom-color: #1e293b; }
    
    .premium-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; }
    .dark .premium-table td { border-bottom-color: #1e293b; }

    .amount-text { font-weight: 900; color: #1e293b; }
    .dark .amount-text { color: #ffffff !important; }

    /* Status Badges */
    .status-badge {
        padding: 5px 12px; border-radius: 8px; font-size: 10px;
        font-weight: 900; text-transform: uppercase; display: inline-block;
    }
    .st-paid { background: #d1fae5; color: #065f46; }
    .st-unpaid { background: #fee2e2; color: #991b1b; }
    .st-pending { background: #fef3c7; color: #92400e; }
    .st-cancelled { background: #dbba72; color: #475569; }
    
    .dark .st-paid { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .dark .st-unpaid { background: rgba(239, 68, 68, 0.2); color: #f87171; }

    /* Pagination */
    .page-btn {
        width: 35px; height: 35px; border-radius: 10px; border: none;
        font-weight: 800; cursor: pointer; transition: 0.3s;
    }
    .page-btn-inactive { background: #f1f5f9; color: #64748b; }
    .dark .page-btn-inactive { background: #1e293b; color: #94a3b8; }
    .page-btn-active { background: #6366f1 !important; color: white !important; }
</style>

<div class="dashboard-main-body">
    <div class="dashboard-content-wrapper">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Finance Dashboard</h1>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Project A Performance</p>
            </div>
            <a href="add.php" class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-xs font-black shadow-lg transition-all flex items-center gap-2">
                <iconify-icon icon="solar:add-circle-bold"></iconify-icon>
                NEW INVOICE
            </a>
        </div>

        <div class="grid grid-cols-12 gap-6 mb-8">
            <div class="col-span-12 md:col-span-4 stat-card-pop accent-revenue">
                <div class="icon-circle bg-revenue"><iconify-icon icon="solar:round-alt-arrow-up-bold"></iconify-icon></div>
                <p class="text-[10px] font-black text-slate-400 uppercase">Revenue</p>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-500">₹<?= number_format($revenue, 2) ?></p>
            </div>
            <div class="col-span-12 md:col-span-4 stat-card-pop accent-pending">
                <div class="icon-circle bg-pending"><iconify-icon icon="solar:danger-circle-bold"></iconify-icon></div>
                <p class="text-[10px] font-black text-slate-400 uppercase">Pending</p>
                <p class="text-2xl font-black text-rose-600 dark:text-rose-500">₹<?= number_format($pending, 2) ?></p>
            </div>
            <div class="col-span-12 md:col-span-4 stat-card-pop accent-total">
                <div class="icon-circle bg-total"><iconify-icon icon="solar:bill-list-bold"></iconify-icon></div>
                <p class="text-[10px] font-black text-slate-400 uppercase">Total Invoices</p>
                <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400"><?= $inv_count ?></p>
            </div>
        </div>

        <div class="activity-container">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-wider">Recent Activity</h2>
                <div class="flex gap-2">
                    <?php for($i = 1; $i <= min($total_pages, 5); $i++): ?>
                        <button onclick="changePage(<?= $i ?>, this)" class="page-btn transition-all <?= ($i==1)?'page-btn-active':'page-btn-inactive' ?>">
                            <?= $i ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th class="text-right">Grand Total</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-rows">
                        <?php include 'fetch_dash_data.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function changePage(page, btn) {
    const rows = document.getElementById('invoice-rows');
    rows.style.opacity = '0.3';
    fetch('fetch_dash_data.php?page=' + page)
        .then(res => res.text())
        .then(html => {
            rows.innerHTML = html;
            rows.style.opacity = '1';
            document.querySelectorAll('.page-btn').forEach(b => {
                b.classList.remove('page-btn-active');
                b.classList.add('page-btn-inactive');
            });
            btn.classList.remove('page-btn-inactive');
            btn.classList.add('page-btn-active');
        });
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>