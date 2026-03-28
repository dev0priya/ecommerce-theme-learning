<?php 
require '../include/load.php'; 
checkLogin(); 

// Stats Queries - NO CHANGES
$rev_query = $pdo->query("SELECT SUM(grand_total) as total FROM invoices WHERE status = 'paid'")->fetch();
$revenue = $rev_query['total'] ?? 0;

$pend_query = $pdo->query("SELECT SUM(grand_total) as total FROM invoices WHERE status = 'unpaid'")->fetch();
$pending = $pend_query['total'] ?? 0;

$count_query = $pdo->query("SELECT COUNT(id) as count FROM invoices")->fetch();
$inv_count = $count_query['count'] ?? 0;

$limit = 7;
$total_rows = $pdo->query("SELECT COUNT(id) FROM invoices")->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$title = 'Financial Dashboard';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-emerald: #10b981;
        --pop-rose: #f43f5e;
        --slate-900: #0f172a;
    }

    .dashboard-main-body { background: #f8fafc; min-height: 100vh; }

    /* Highlighted Stat Cards */
    .stat-card-pop {
        background: white;
        padding: 25px;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-card-pop:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
    }

    /* Vibrant Accents for Each Box */
    .accent-revenue { border-bottom: 6px solid var(--pop-emerald); }
    .accent-pending { border-bottom: 6px solid var(--pop-rose); }
    .accent-total { border-bottom: 6px solid var(--pop-indigo); }

    /* Icon Background Pop */
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .bg-revenue { background: #d1fae5; color: #059669; }
    .bg-pending { background: #fee2e2; color: #dc2626; }
    .bg-total { background: #e0e7ff; color: #4f46e5; }

    /* Status Badges */
    .status-pill {
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-unpaid { background: #fee2e2; color: #991b1b; }

    /* --- PAGINATION STYLING (ACTIVE & HOVER) --- */
    .page-btn {
        cursor: pointer;
        border: none;
        outline: none;
        transition: all 0.3s ease;
    }

    /* Inactive State Hover */
    .page-btn.bg-slate-50:hover {
        background-color: var(--pop-indigo) !important;
        color: white !important;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);
    }

    /* Active State (Current Page) */
    .page-btn-active {
        background-color: var(--slate-900) !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        transform: translateY(-2px);
    }

</style>

<div class="dashboard-main-body px-8 py-10">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Financial Overview</h1>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1">Project A Real-time Stats</p>
        </div>
        <a href="add.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-xs font-black shadow-lg shadow-indigo-200 transition-all flex items-center gap-2">
            <iconify-icon icon="solar:add-circle-bold" class="text-xl"></iconify-icon>
            NEW INVOICE
        </a>
    </div>

    <div class="grid grid-cols-12 gap-6 mb-10">
        <div class="col-span-12 md:col-span-4">
            <div class="stat-card-pop accent-revenue">
                <div class="icon-circle bg-revenue">
                    <iconify-icon icon="solar:round-alt-arrow-up-bold" class="text-2xl"></iconify-icon>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Revenue</p>
                <p class="text-3xl font-black text-emerald-600 mt-1">₹<?= number_format($revenue, 2) ?></p>
            </div>
        </div>

        <div class="col-span-12 md:col-span-4">
            <div class="stat-card-pop accent-pending">
                <div class="icon-circle bg-pending">
                    <iconify-icon icon="solar:danger-circle-bold" class="text-2xl"></iconify-icon>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending Balance</p>
                <p class="text-3xl font-black text-rose-600 mt-1">₹<?= number_format($pending, 2) ?></p>
            </div>
        </div>

        <div class="col-span-12 md:col-span-4">
            <div class="stat-card-pop accent-total">
                <div class="icon-circle bg-total">
                    <iconify-icon icon="solar:bill-list-bold" class="text-2xl"></iconify-icon>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Invoices</p>
                <p class="text-3xl font-black text-indigo-600 mt-1"><?= $inv_count ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-slate-800">Recent Activity</h2>
            <div class="flex gap-2">
                <?php for($i = 1; $i <= min($total_pages, 5); $i++): ?>
                    <button onclick="changePage(<?= $i ?>, this)" class="page-btn w-9 h-9 rounded-xl font-black text-xs transition-all <?= ($i==1)?'page-btn-active':'bg-slate-50 text-slate-400' ?>">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-50">
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="invoice-rows" class="divide-y divide-slate-50">
                    <?php include 'fetch_dash_data.php'; ?>
                </tbody>
            </table>
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
            
            // Sabhi buttons ki class reset karo
            document.querySelectorAll('.page-btn').forEach(b => {
                b.className = 'page-btn w-9 h-9 rounded-xl font-black text-xs transition-all bg-slate-50 text-slate-400';
            });
            
            // Click kiye gaye button ko Active class do
            btn.className = 'page-btn w-9 h-9 rounded-xl font-black text-xs transition-all page-btn-active';
        });
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>