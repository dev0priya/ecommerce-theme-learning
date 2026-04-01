<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'Product Categories';
$subTitle = 'E-Commerce / Management';

// Category Save Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    if (!empty($category_name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$category_name]);
        header("Location: categories.php");
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Reset & Smoothness */
    html, body { max-width: 100%; overflow-x: hidden; scroll-behavior: smooth; }

    /* Luxury Glass Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .dark .glass-card {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(51, 65, 85, 0.4);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
    }

    /* Gradient Input Focus */
    .premium-input {
        width: 100%;
        padding: 12px 18px;
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .premium-input {
        background: #1e293b;
        color: #f1f5f9;
    }
    .premium-input:focus {
        background: white;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    /* Action Buttons with Glow */
    .btn-gradient {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        padding: 14px 28px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: 0.5px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    }
    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
    }

    /* Table Enhancements */
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .modern-table th {
        padding: 12px 20px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
    }
    .modern-table tr td {
        padding: 16px 20px;
        background: white;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .modern-table tr td {
        background: rgba(30, 41, 59, 0.4);
        border-color: rgba(51, 65, 85, 0.3);
        color: #f1f5f9;
    }
    .modern-table tr td:first-child { border-left: 1px solid #f1f5f9; border-radius: 12px 0 0 12px; }
    .modern-table tr td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 12px 12px 0; }
    
    .dark .modern-table tr td:first-child { border-left-color: rgba(51, 65, 85, 0.3); }
    .dark .modern-table tr td:last-child { border-right-color: rgba(51, 65, 85, 0.3); }

    /* Icon Badge */
    .icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    @media (max-width: 991px) {
        .grid-layout { display: flex; flex-direction: column; }
    }
</style>

<div class="dashboard-main-body px-6 py-10 sm:px-12">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="h-1 w-10 bg-indigo-600 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-indigo-600">Inventory System</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Categories</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">Create and refine your product classifications.</p>
            </div>
            <div>
                
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8 grid-layout">
            <div class="col-span-12 lg:col-span-4">
                <div class="glass-card sticky top-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                            <iconify-icon icon="solar:folder-add-bold-duotone" class="text-2xl text-indigo-600"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Quick Add</h3>
                    </div>

                    <form action="categories.php" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">New Classification Name</label>
                            <input type="text" name="category_name" class="premium-input" placeholder="e.g. Minimalist Decor" required>
                        </div>
                        <button type="submit" name="add_category" class="btn-gradient w-full flex justify-center items-center gap-2">
                            <iconify-icon icon="solar:magic-stick-3-bold" class="text-lg"></iconify-icon>
                            Confirm Category
                        </button>
                    </form>

                    <div class="mt-10 p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden shadow-xl">
                        <div class="relative z-10">
                            <h5 class="text-xs font-black uppercase tracking-wider mb-2 opacity-60">Insight</h5>
                            <p class="text-[11px] leading-relaxed font-medium opacity-90">Organized categories increase customer conversion by up to 35%.</p>
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-indigo-500/20 rounded-full blur-2xl"></div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <div class="glass-card !p-0 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[2px]">Management List</h3>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                            <span class="text-[10px] font-bold text-slate-400"><?= count($categories) ?> Active Items</span>
                        </div>
                    </div>

                    <div class="p-6 overflow-x-auto">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Classification Name</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="font-mono text-[11px] text-slate-400">ID-<?= $cat['id'] ?></td>
                                    <td class="font-black text-slate-800 dark:text-slate-200"><?= e($cat['name']) ?></td>
                                    <td>
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="#" class="icon-badge bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white dark:bg-indigo-900/20">
                                                <iconify-icon icon="solar:pen-bold-duotone" class="text-lg"></iconify-icon>
                                            </a>
                                            <a href="#" onclick="return confirm('Delete this classification?')" class="icon-badge bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-900/20">
                                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="text-lg"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($categories)): ?>
                            <div class="text-center py-20">
                                <iconify-icon icon="solar:box-minimalistic-broken" class="text-6xl text-slate-200 mb-4"></iconify-icon>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">No classifications found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>