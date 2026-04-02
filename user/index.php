<?php 
require '../include/load.php'; 
checkLogin(); 

// 1. Pagination & Fetch Logic (12 Rows Limit)
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_rows = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

$title = 'User Directory';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Adaptive Background */
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Premium Content Card */
    .user-card-container {
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
    .dark .user-card-container { background: #0f172a; border-color: #1e293b; }

    /* --- REGISTER BUTTON VISIBILITY --- */
    .btn-register-adaptive {
        background: #6366f1;
        color: #000000 !important; /* Black text for Light Theme */
        padding: 16px 32px;
        border-radius: 16px;
        font-weight: 900;
        font-size: 12px;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .dark .btn-register-adaptive {
        color: #ffffff !important; /* White text for Dark Theme */
        background: #4f46e5;
    }

    /* --- TABLE STYLING --- */
    .user-table { width: 100%; border-collapse: collapse; }
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
        padding: 18px 20px;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .dark .table-td { border-bottom-color: #1e293b; }

    /* --- AVATAR & ACTION CENTERING (FIXED) --- */
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;             /* Flexbox for centering */
        align-items: center;       /* Vertical center */
        justify-content: center;    /* Horizontal center */
        font-weight: 900;
        font-size: 18px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
    }

    .btn-circle-action {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;             /* Flexbox for centering */
        align-items: center;       /* Vertical center */
        justify-content: center;    /* Horizontal center */
        transition: 0.3s;
        font-size: 18px;
        border: none;
    }

    /* Role Badges */
    .role-badge {
        padding: 6px 14px; border-radius: 10px; font-size: 10px;
        font-weight: 900; text-transform: uppercase; display: inline-block;
    }
    .role-admin { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .role-user { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .dark .role-admin { background: rgba(124, 58, 237, 0.15); color: #a78bfa; border-color: rgba(167, 139, 250, 0.2); }
    .dark .role-user { background: rgba(22, 163, 74, 0.15); color: #4ade80; border-color: rgba(74, 222, 128, 0.2); }

    .btn-edit { background: #f1f5f9; color: #6366f1; }
    .dark .btn-edit { background: #1e293b; color: #818cf8; }
    .btn-delete { background: #fff1f2; color: #f43f5e; }
    .dark .btn-delete { background: rgba(244, 63, 94, 0.1); color: #fb7185; }

    /* Pagination */
    .page-btn {
        width: 40px; height: 40px; border-radius: 12px; font-weight: 800; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .page-active { background: #6366f1; color: white !important; }
    .page-inactive { background: #f1f5f9; color: #64748b; }
    .dark .page-inactive { background: #1e293b; color: #94a3b8; }
</style>

<div class="dashboard-main-body px-6 py-10 lg:px-10">
    <div class="user-card-container shadow-xl">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-10 h-1 bg-indigo-600 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-indigo-600">Access Control</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">User Management</h1>
                <p class="text-slate-400 dark:text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Total Members: <?= $total_rows ?></p>
            </div>
            
            <a href="add.php" class="btn-register-adaptive">
                <iconify-icon icon="solar:user-plus-bold" class="text-xl"></iconify-icon>
                REGISTER NEW USER
            </a>
        </div>

        <div class="overflow-x-auto flex-grow">
            <table class="user-table">
                <thead>
                    <tr>
                        <th class="table-th text-left">Identity</th>
                        <th class="table-th text-left">Email Address</th>
                        <th class="table-th text-center">Security Role</th>
                        <th class="table-th text-left">Created At</th>
                        <th class="table-th text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                        <td class="table-td">
                            <div class="flex items-center gap-4">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div>
                                    <span class="block font-black text-slate-800 dark:text-white text-sm"><?= e($u['name']) ?></span>
                                    <span class="text-[10px] text-slate-400 font-black uppercase">UID: <?= $u['id'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="table-td text-sm font-bold text-slate-500 dark:text-slate-400">
                            <?= e($u['email']) ?>
                        </td>
                        <td class="table-td text-center">
                            <span class="role-badge <?= ($u['role'] === 'admin') ? 'role-admin' : 'role-user' ?>">
                                <?= e($u['role']) ?>
                            </span>
                        </td>
                        <td class="table-td text-xs font-black text-slate-400 uppercase tracking-tighter">
                            <?= date('d M, Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td class="table-td">
                            <div class="flex items-center justify-end gap-3">
                                <a href="edit.php?id=<?= $u['id'] ?>" class="btn-circle-action btn-edit" title="Edit Profile">
                                    <iconify-icon icon="solar:pen-new-square-bold-duotone"></iconify-icon>
                                </a>
                                <button type="button" onclick="deleteItem(<?= $u['id'] ?>, 'users')" class="btn-circle-action btn-delete" title="Remove User">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-12 flex justify-center items-center gap-2">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="page-btn <?= ($i == $page) ? 'page-active' : 'page-inactive' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>