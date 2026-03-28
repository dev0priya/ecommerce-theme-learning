<?php 
require '../include/load.php'; 
checkLogin(); 

$limit = 10;
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

$title = 'Manage Invoices';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-emerald: #10b981;
        --pop-amber: #f59e0b;
        --pop-rose: #f43f5e;
        --slate-900: #0f172a;
    }

    .dashboard-main-body { background: #f8fafc; min-height: 100vh; }

    /* Pop-up Search Input */
    .pop-input {
        width: 100%;
        padding: 14px 16px 14px 45px;
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 18px;
        font-weight: 700;
        color: var(--slate-900);
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .pop-input:focus {
        border-color: var(--pop-indigo);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }
    .pop-input::placeholder { color: #94a3b8; font-weight: 600; }

    /* Action Buttons Pop */
    .btn-view-pop {
        background: var(--brand-light);
        color: var(--pop-indigo);
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 900;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: 0.2s;
        border: 1px solid transparent;
    }
    .btn-view-pop:hover {
        background: var(--pop-indigo);
        color: white;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);
    }

    /* Status Pills with Pop Colors */
    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }
    .status-paid { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .status-unpaid { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Pagination Pop Numbers */
    .page-link-pop {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-weight: 900;
        transition: 0.3s;
        text-decoration: none;
    }
    .page-active {
        background: var(--pop-indigo);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }
    .page-inactive {
        background: white;
        color: #94a3b8;
        border: 1px solid #f1f5f9;
    }
    .page-inactive:hover {
        border-color: var(--pop-indigo);
        color: var(--pop-indigo);
        transform: translateY(-3px);
    }
</style>

<div class="dashboard-main-body px-10 py-12">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 border-t-8 border-indigo-600">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Invoice Repository</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Project A Database</p>
            </div>
            <div class="relative w-96">
                <form action="" method="GET">
                    <input type="text" name="search" value="<?= e($search) ?>" class="pop-input" placeholder="Search customer or #INV number...">
                    <iconify-icon icon="solar:minimalistic-magnifer-bold-duotone" class="absolute left-4 top-4 text-2xl text-indigo-500"></iconify-icon>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-50 mb-10 shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr class="text-slate-400 text-[11px] font-black uppercase tracking-[0.15em]">
                        <th class="px-8 py-6">Invoice #</th>
                        <th class="px-8 py-6">Customer Name</th>
                        <th class="px-8 py-6">Issued Date</th>
                        <th class="px-8 py-6">Grand Total</th>
                        <th class="px-8 py-6 text-center">Status</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach($invoices as $row): ?>
                    <tr class="hover:bg-indigo-50/20 transition-all group">
                        <td class="px-8 py-6 font-black text-indigo-600">#<?= $row['invoice_number'] ?></td>
                        <td class="px-8 py-6 font-black text-slate-800 text-sm"><?= e($row['customer_name']) ?></td>
                        <td class="px-8 py-6 text-slate-400 text-xs font-bold"><?= date('d M, Y', strtotime($row['created_at'])) ?></td>
                        <td class="px-8 py-6 font-black text-slate-900 text-lg">₹<?= number_format($row['grand_total'], 2) ?></td>
                        <td class="px-8 py-6 text-center">
                            <span class="status-badge <?= ($row['status']=='paid')?'status-paid':'status-unpaid' ?>">
                                <?= strtoupper($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="view.php?id=<?= $row['id'] ?>" class="btn-view-pop">
                                View Details
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex justify-center gap-3">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= $search ?>" class="page-link-pop <?= ($i==$page)?'page-active':'page-inactive' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>