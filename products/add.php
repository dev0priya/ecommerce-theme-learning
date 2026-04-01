<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'Create Masterpiece';
$subTitle = 'E-Commerce / Inventory';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Collect Data from Form (Names must match HTML 'name' attribute)
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $category_id  = $_POST['category_id'];
    $description  = $_POST['description']; // Catching description
    $stock        = $_POST['stock'];       // Catching stock
    $colors       = $_POST['colors'];      // Catching colors
    $sizes        = $_POST['sizes'];       // Catching sizes

    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image_name);
    }

    // 2. Updated SQL Query (Ensure all 8 columns are present)
    $sql = "INSERT INTO products (product_name, price, category_id, image, description, stock, colors, sizes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // 3. Execute with all variables in correct order
    $stmt->execute([
        $product_name, 
        $price, 
        $category_id, 
        $image_name, 
        $description, 
        $stock, 
        $colors, 
        $sizes
    ]);
    
    header("Location: index.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    html, body { max-width: 100%; overflow-x: hidden; margin: 0; }
    .admin-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; width: 100%; }
    .dark .admin-card { background: #111827; border-color: #1f2937; }
    .admin-input { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; }
    .dark .admin-input { background: #1f2937; border-color: #374151; color: white; }
    .admin-input:focus { border-color: #4f46e5; ring: 2px rgba(79, 70, 229, 0.1); }
    
    .image-drop-zone { border: 2px dashed #e2e8f0; border-radius: 10px; height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: #f8fafc; position: relative; overflow: hidden; }
    .dark .image-drop-zone { background: #1f2937; border-color: #374151; }

    .action-bar { position: sticky; bottom: 0; width: 100%; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-top: 1px solid #e2e8f0; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; align-items: center; gap: 16px; z-index: 100; box-sizing: border-box; }
    .dark .action-bar { background: rgba(15, 23, 42, 0.95); border-color: #1f2937; }

    .btn-save-master { background: #4f46e5; color: white; padding: 12px 32px; border-radius: 10px; font-weight: 700; font-size: 14px; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); border: none; cursor: pointer; }
    .btn-save-master:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4); }

    @media (max-width: 768px) { .grid-cols-12 { display: flex; flex-direction: column; } }
</style>

<div class="dashboard-main-body px-4 py-8 sm:px-10">
    <form action="add.php" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Create Masterpiece</h1>
            <p class="text-slate-500 font-medium">Add all details to avoid empty fields in database.</p>
        </div>

        <div class="grid grid-cols-12 gap-0 sm:gap-10">
            <div class="col-span-12 lg:col-span-8">
                <div class="admin-card shadow-sm">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Product Name</label>
                            <input type="text" name="product_name" class="admin-input" required>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Detailed Description</label>
                            <textarea name="description" rows="4" class="admin-input" placeholder="Enter product story..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="admin-input" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Category</label>
                                <select name="category_id" class="admin-input" required>
                                    <option value="">Choose...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-card shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-6 tracking-widest">Configuration & Variants</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-2">Available Colors</label>
                            <input type="text" name="colors" class="admin-input" placeholder="e.g. Red, Blue">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-2">Available Sizes</label>
                            <input type="text" name="sizes" class="admin-input" placeholder="e.g. S, M, L">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="admin-card shadow-sm">
                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-4">Product Visual</label>
                    <div class="image-drop-zone group">
                        <input type="file" name="image" id="imgInput" class="absolute inset-0 opacity-0 z-30 cursor-pointer" accept="image/*">
                        <div id="placeholder" class="text-center">
                            <iconify-icon icon="solar:camera-add-bold" class="text-3xl text-slate-300"></iconify-icon>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Upload Image</p>
                        </div>
                        <img id="imgPreview" src="#" class="hidden w-full h-full object-contain p-2 relative z-20">
                    </div>
                </div>

                <div class="admin-card shadow-sm">
                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Inventory Stock</label>
                    <input type="number" name="stock" class="admin-input" value="0">
                </div>
            </div>
        </div>

        <div class="action-bar">
            <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-rose-500">Discard</a>
            <button type="submit" class="btn-save-master">
                <iconify-icon icon="solar:diskette-bold" class="text-lg"></iconify-icon>
                Save & Publish
            </button>
        </div>
    </form>
</div>

<script>
    const imgInput = document.getElementById('imgInput');
    const imgPreview = document.getElementById('imgPreview');
    const placeholder = document.getElementById('placeholder');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>