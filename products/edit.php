<?php
require '../include/load.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit(); }

// 1. Fetch current product data (including new columns)
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { die("Product not found."); }

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$title = 'Refine Product';
$subTitle = 'Management / Editor';

// 2. Update Logic for all 8 fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $category_id  = $_POST['category_id'];
    $description  = $_POST['description'];
    $stock        = $_POST['stock'];
    $colors       = $_POST['colors'];
    $sizes        = $_POST['sizes'];
    $imageName    = $product['image']; 

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $imageName);
    }

    $sql = "UPDATE products SET 
            product_name=?, price=?, category_id=?, image=?, 
            description=?, stock=?, colors=?, sizes=? 
            WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_name, $price, $category_id, $imageName, $description, $stock, $colors, $sizes, $id]);
    
    header('Location: index.php');
    exit();
}

include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Consistent Admin Theme */
    .admin-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .dark .admin-card { background: #111827; border-color: #1f2937; }

    .form-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
        letter-spacing: 0.5px;
    }

    .admin-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }
    .dark .admin-input { background: #1f2937; border-color: #374151; color: white; }
    .admin-input:focus { border-color: #4f46e5; ring: 3px rgba(79, 70, 229, 0.1); }

    /* Compact Media Box */
    .image-edit-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }
    .dark .image-edit-zone { background: #1f2937; border-color: #374151; }

    /* Sticky Footer */
    .action-bar {
        position: sticky;
        bottom: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 16px;
        z-index: 100;
        margin-top: 2rem;
    }
    .dark .action-bar { background: rgba(15, 23, 42, 0.95); border-color: #1f2937; }

    .btn-update {
        background: #4f46e5;
        color: white;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .btn-update:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
    }
</style>

<div class="dashboard-main-body px-4 py-8 sm:px-10">
    <form method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto">
        
        <div class="mb-8 flex justify-between items-end">
            <div>
                <nav class="flex mb-2 text-[10px] font-bold text-slate-400 gap-2 uppercase">
                    <a href="index.php">Inventory</a>
                    <span>/</span>
                    <span class="text-indigo-600">Edit Product</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Refine Masterpiece</h1>
            </div>
            <div class="hidden sm:block">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase">Live on Store</span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-0 sm:gap-10">
            <div class="col-span-12 lg:col-span-8">
                <div class="admin-card shadow-sm">
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="admin-input" value="<?= e($product['product_name']) ?>" required>
                        </div>
                        
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="5" class="admin-input" placeholder="Update product details..."><?= e($product['description'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Price (USD)</label>
                                <input type="number" step="0.01" name="price" class="admin-input font-bold" value="<?= e($product['price']) ?>" required>
                            </div>
                            <div>
                                <label class="form-label">Category</label>
                                <select name="category_id" class="admin-input font-bold cursor-pointer" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= e($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest">Configuration & Styles</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Colors</label>
                            <input type="text" name="colors" class="admin-input" placeholder="e.g. Black, White" value="<?= e($product['colors'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="form-label">Sizes</label>
                            <input type="text" name="sizes" class="admin-input" placeholder="e.g. S, M, L, XL" value="<?= e($product['sizes'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="admin-card shadow-sm">
                    <label class="form-label mb-4">Product Visual</label>
                    <div class="image-edit-zone group">
                        <input type="file" name="image" id="imgInput" class="absolute inset-0 opacity-0 z-30 cursor-pointer" accept="image/*">
                        <?php $img = !empty($product['image']) ? $product['image'] : 'default.png'; ?>
                        <img id="imgPreview" src="../assets/uploads/<?= $img ?>" class="w-full h-full object-contain p-2 relative z-20 transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all z-25 pointer-events-none">
                            <iconify-icon icon="solar:camera-bold" class="text-white text-3xl"></iconify-icon>
                        </div>
                    </div>
                    <p class="text-[10px] text-center text-slate-400 mt-3 font-bold">Click image to change asset</p>
                </div>

                <div class="admin-card shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Inventory Stock</label>
                            <input type="number" name="stock" class="admin-input" value="<?= e($product['stock'] ?? 0) ?>">
                        </div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between items-center text-[11px] font-bold">
                                <span class="text-slate-400 uppercase">System ID</span>
                                <span class="text-slate-900 dark:text-white">#PROD-<?= $id ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-bar border-t">
            <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-rose-500 transition-colors">Cancel</a>
            <button type="submit" class="btn-update">
                <iconify-icon icon="solar:check-read-bold" class="text-xl"></iconify-icon>
                Save & Update Product
            </button>
        </div>
    </form>
</div>

<script>
    const imgInput = document.getElementById('imgInput');
    const imgPreview = document.getElementById('imgPreview');
    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
        }
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>