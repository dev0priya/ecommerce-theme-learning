<?php 
require '../include/load.php'; 
checkLogin(); 

$limit = 12; // As per your request for 12 rows
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['search'] ?? '';

$where = " WHERE 1=1 ";
if (!empty($search)) {
    if (strtolower($search) == 'unpaid' || strtolower($search) == 'paid') {
        $where .= " AND i.status = '$search' ";
    } else {
        $where .= " AND (i.invoice_number LIKE '%$search%' OR c.name LIKE '%$search%') ";
    }
}

$total_rows = $pdo->query("SELECT COUNT(*) FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id $where")->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$query = $pdo->prepare("SELECT i.*, c.name as customer_name FROM invoices i 
                       LEFT JOIN customers c ON i.customer_id = c.id 
                       $where ORDER BY i.created_at DESC LIMIT $limit OFFSET $offset");
$query->execute();
$invoices = $query->fetchAll();

$title = 'Invoice Repository';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Adaptive Background */
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Premium Content Wrapper */
    .content-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        width: 100%;
        min-height: 800px;
        display: flex;
        flex-direction: column;
        transition: 0.3s;
    }
    .dark .content-card { background: #0f172a; border-color: #1e293b; }

    /* Adaptive Search Bar */
    .search-wrapper { position: relative; width: 100%; max-width: 450px; }
    .premium-search {
        width: 100%;
        padding: 14px 20px 14px 50px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 18px;
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .premium-search { background: #1e293b; color: #f1f5f9; }
    .premium-search:focus {
        background: white;
        border-color: #6366f1;
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.15);
        outline: none;
    }
    .dark .premium-search:focus { background: #0f172a; }

    /* Table Styling */
    .premium-table { width: 100%; border-collapse: collapse; }
    .table-th {
        padding: 20px;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .table-th { border-bottom-color: #1e293b; }
    
    .table-td {
        padding: 20px;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .dark .table-td { border-bottom-color: #1e293b; }

    /* Amount Visibility */
    .amount-text { font-weight: 900; font-size: 16px; color: #1e293b; }
    .dark .amount-text { color: #ffffff !important; }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        display: inline-block;
    }
    .st-paid { background: #d1fae5; color: #065f46; }
    .st-unpaid { background: #fee2e2; color: #991b1b; }
    .dark .st-paid { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .dark .st-unpaid { background: rgba(239, 68, 68, 0.2); color: #f87171; }

    /* View Button Adaptive */
    .btn-action-view {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f1f5f9;
        color: #6366f1;
        transition: 0.3s;
    }
    .dark .btn-action-view { background: #1e293b; color: #818cf8; }
    .btn-action-view:hover {
        background: #6366f1;
        color: white !important;
        transform: translateY(-3px);
    }

    /* Pagination */
    .page-btn {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-weight: 800;
        transition: 0.3s;
    }
    .page-active {
        background: #6366f1;
        color: white !important;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }
    .page-inactive {
        background: #f1f5f9;
        color: #64748b;
    }
    .dark .page-inactive { background: #1e293b; color: #94a3b8; }
    .page-inactive:hover { background: #6366f1; color: white; }
</style>

<div class="dashboard-main-body px-6 py-10 lg:px-10">
    <div class="content-card shadow-xl">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-10 h-1 bg-indigo-600 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-indigo-600">Database Engine</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Manage Invoices</h1>
                <p class="text-slate-400 dark:text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Total Records: <?= $total_rows ?></p>
            </div>

            <div class="search-wrapper">
                <form action="" method="GET">
                    <iconify-icon icon="solar:minimalistic-magnifer-bold-duotone" class="absolute left-4 top-1/2 -translate-y-1/2 text-2xl text-slate-400"></iconify-icon>
                    <input type="text" name="search" value="<?= e($search) ?>" class="premium-search" placeholder="Search Invoice # or Customer...">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto flex-grow">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="table-th text-left">Invoice #</th>
                        <th class="table-th text-left">Customer Details</th>
                        <th class="table-th text-left">Issued Date</th>
                        <th class="table-th text-right">Grand Total</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoices as $row): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                        <td class="table-td font-mono text-xs font-bold text-slate-400">#<?= $row['invoice_number'] ?></td>
                        <td class="table-td">
                            <span class="block font-bold text-slate-800 dark:text-white text-sm"><?= e($row['customer_name']) ?></span>
                            <span class="text-[10px] text-slate-400 font-black uppercase">Verified Merchant</span>
                        </td>
                        <td class="table-td text-slate-500 dark:text-slate-400 text-xs font-bold">
                            <?= date('d M, Y', strtotime($row['created_at'])) ?>
                        </td>
                        <td class="table-td text-right amount-text">
                            ₹<?= number_format($row['grand_total'], 2) ?>
                        </td>
                        <td class="table-td text-center">
                            <span class="status-badge <?= ($row['status']=='paid')?'st-paid':'st-unpaid' ?>">
                                <?= strtoupper($row['status']) ?>
                            </span>
                        </td>
                        <td class="table-td text-right">
                            <a href="view.php?id=<?= $row['id'] ?>" class="btn-action-view" title="View Details">
                                <iconify-icon icon="solar:eye-bold-duotone" class="text-xl"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="6" class="py-32 text-center">
                            <iconify-icon icon="solar:box-minimalistic-broken" class="text-6xl text-slate-200 dark:text-slate-800 mb-4"></iconify-icon>
                            <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">No matching invoices found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-12 flex justify-center items-center gap-3">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&search=<?= $search ?>" class="page-btn page-inactive"><iconify-icon icon="solar:alt-arrow-left-bold"></iconify-icon></a>
            <?php endif; ?>

            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= $search ?>" class="page-btn <?= ($i==$page)?'page-active':'page-inactive' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?= $page+1 ?>&search=<?= $search ?>" class="page-btn page-inactive"><iconify-icon icon="solar:alt-arrow-right-bold"></iconify-icon></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>