<?php 
require '../include/load.php'; 
checkLogin(); 

// Category Add Logic
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: categories.php");
        exit();
    }
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c ORDER BY c.id DESC")->fetchAll();

$title = 'Taxonomy Control';
include '../partials/layouts/layoutTop.php'; 
?>

<link rel="stylesheet" href="../assets/css/pages/product-style.css">

<div class="dashboard-main-body premium-gradient-bg px-10 py-12">
    <div class="flex items-center justify-between mb-12">
        <div>
            <span class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.4em] mb-1 block">Management Mode</span>
            <h1 class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 tracking-tight">
                Taxonomy Control
            </h1>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] mt-2 ml-1">Organize Collections • 2026</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-10">
        <div class="col-span-12 lg:col-span-4">
            <div class="vibrant-card p-8 border-t-4 border-indigo-600">
                <h4 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Quick Add
                </h4>
                <form action="categories.php" method="POST">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Category Name</label>
                            <input type="text" name="name" class="pop-input w-full" placeholder="e.g. Luxury Watches" required>
                        </div>
                        <button type="submit" name="add_category" class="btn-pop w-full justify-center shadow-2xl">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="text-2xl"></iconify-icon>
                            Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-8">
            <div class="vibrant-card p-8 border-t-4 border-purple-500">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-black text-slate-800 tracking-tight">Active Collections</h4>
                    <div class="relative">
                        <input type="text" id="searchCat" class="pop-input py-2 px-4 text-sm" placeholder="Search categories...">
                    </div>
                </div>
                
                <div class="overflow-hidden rounded-2xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Collection Name</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Volume</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="catTable">
                            <?php foreach ($categories as $cat): ?>
                            <tr class="group border-b border-slate-50 hover:bg-indigo-50/30 transition-all">
                                <td class="px-6 py-6">
                                    <span class="font-extrabold text-slate-700 text-lg"><?= e($cat['name']) ?></span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="bg-indigo-100 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-black">
                                        <?= $cat['product_count'] ?> Products
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="w-10 h-10 bg-white shadow-md rounded-xl flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all">
                                            <iconify-icon icon="solar:pen-bold-duotone" class="text-xl"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/app-logic.js"></script>
<script>
    // Simple Search Logic if app-logic.js is connected
    if(typeof initTableSearch === 'function') {
        initTableSearch('searchCat', 'catTable');
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>