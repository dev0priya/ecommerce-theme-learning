<?php 
    require '../include/load.php'; 
    checkLogin(); 

    // Fetching from ecommerce_db
    $stmt = $pdo->prepare("SELECT * FROM currencies ORDER BY id ASC");
    $stmt->execute();
    $currencies = $stmt->fetchAll();

    $title = 'Currency Management';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }
    .currency-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; padding: 32px; transition: 0.3s; }
    .dark .currency-card { background: #0f172a; border-color: #1e293b; }
    
    /* Table Styling */
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: center; padding: 18px; font-size: 11px; font-weight: 900; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #f1f5f9; letter-spacing: 1px; }
    .dark .premium-table th { border-bottom-color: #1e293b; }
    .premium-table td { padding: 20px; text-align: center; font-size: 14px; font-weight: 700; color: #1e293b; border-bottom: 1px solid #f8fafc; }
    .dark .premium-table td { border-bottom-color: #1e293b; color: #f1f5f9; }

    /* Action Buttons */
    .action-btn-circle { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; font-size: 18px; cursor: pointer; border: none; }
    .btn-edit { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .btn-delete { background: #fff1f2; color: #f43f5e; border: 1px solid #ffe4e6; }
    .dark .btn-edit { background: rgba(22, 163, 74, 0.1); border-color: rgba(34, 197, 94, 0.2); }
    .dark .btn-delete { background: rgba(244, 63, 94, 0.1); border-color: rgba(244, 63, 94, 0.2); }

    .btn-add-currency { background: #6366f1; color: white !important; padding: 12px 24px; border-radius: 14px; font-weight: 900; font-size: 12px; text-transform: uppercase; display: flex; align-items: center; gap: 10px; }
</style>

<div class="dashboard-main-body px-6 py-10">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Currencies</h1>
        <a href="add-currency.php" class="btn-add-currency">Add Currency</a>
    </div>

    <div class="currency-card shadow-sm">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($currencies as $index => $c): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all">
                        <td class="text-xs font-black text-slate-400"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="font-black text-slate-800 dark:text-white"><?= e($c['name']) ?> <?= $c['is_default'] ? '<span class="text-[10px] text-indigo-500">(Default)</span>' : '' ?></td>
                        <td class="text-lg text-indigo-600 font-black"><?= e($c['symbol']) ?></td>
                        <td class="text-sm font-black uppercase text-slate-500 dark:text-slate-400"><?= e($c['code']) ?></td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?= $c['status'] ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' ?>">
                                <?= $c['status'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="edit-currency.php?id=<?= $c['id'] ?>" class="action-btn-circle btn-edit"><iconify-icon icon="solar:pen-new-square-bold"></iconify-icon></a>
                                <button onclick="confirmDelete(<?= $c['id'] ?>, <?= $c['is_default'] ?>)" class="action-btn-circle btn-delete">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-bold"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, isDefault) {
    if(isDefault) {
        alert("Cannot delete the default currency!");
        return;
    }
    if (confirm("Are you sure you want to delete this currency?")) {
        window.location.href = "delete-currency.php?id=" + id;
    }
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>