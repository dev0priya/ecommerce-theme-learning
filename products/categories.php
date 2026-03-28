<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'Create Masterpiece';
$subTitle = 'E-Commerce / Inventory';

// Form Logic (Stable PHP - No Changes)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $image_name = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO products (product_name, price, category_id, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_name, $price, $category_id, $image_name]);
    header("Location: index.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-purple: #a855f7;
        --slate-900: #0f172a;
        --bg-soft: #f8fafc;
    }

    .dashboard-main-body { background: var(--bg-soft); min-height: 100vh; }

    /* Compact Cards */
    .vibrant-card-pop {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    /* Input Field Pop */
    .pop-input-styled {
        width: 100%;
        padding: 10px 15px;
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 12px;
        font-weight: 700;
        color: var(--slate-900);
        outline: none;
        transition: 0.2s;
    }
    .pop-input-styled:focus {
        background: white;
        border-color: var(--pop-indigo);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    /* Primary Pop Button */
    .btn-create-pop {
        background: linear-gradient(135deg, var(--pop-indigo) 0%, var(--pop-purple) 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
    }
    .btn-create-pop:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(99, 102, 241, 0.4);
    }

    /* Upload Area Styling */
    .upload-box {
        border: 2px dashed #e2e8f0;
        border-radius: 1.25rem;
        background: #fbfdff;
        transition: 0.3s;
    }
    .upload-box:hover { border-color: var(--pop-indigo); background: #f8fafc; }

    /* Glass Status Badge */
    .status-glass-mini {
        background: linear-gradient(135deg, var(--pop-indigo), var(--pop-purple));
        color: white;
        padding: 15px;
        border-radius: 1.25rem;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }
</style>

<div class="dashboard-main-body px-6 py-8">
    <form action="add.php" method="POST" enctype="multipart/form-data">
        
        <div class="flex items-center justify-between mb-8 max-w-5xl mx-auto">
            <div>
                <span class="text-indigo-600 font-black text-[9px] uppercase tracking-widest block mb-1">Creation Mode</span>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">New Masterpiece</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="index.php" class="text-slate-400 font-bold text-xs uppercase hover:text-rose-500 transition-colors">Discard</a>
                <button type="submit" class="btn-create-pop">
                    <iconify-icon icon="solar:magic-stick-3-bold-duotone" class="text-xl"></iconify-icon>
                    Create Product
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 max-w-5xl mx-auto">
            <div class="col-span-12 lg:col-span-8">
                <div class="vibrant-card-pop p-6 border-t-8 border-indigo-600">
                    <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="text-indigo-600"></iconify-icon>
                        Product Details
                    </h4>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Product Title</label>
                            <input type="text" name="product_name" class="pop-input-styled text-sm" placeholder="Premium item name..." required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="pop-input-styled text-base font-black text-indigo-600" placeholder="0.00" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Category</label>
                                <select name="category_id" class="pop-input-styled text-sm font-bold cursor-pointer" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="vibrant-card-pop p-6">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Visual Asset</h4>
                    <div class="upload-box relative p-6 text-center group cursor-pointer overflow-hidden">
                        <input type="file" name="image" id="imgInput" class="absolute inset-0 opacity-0 z-30 cursor-pointer" accept="image/*">
                        
                        <div id="placeholderView" class="py-4">
                            <iconify-icon icon="solar:gallery-add-bold-duotone" class="text-4xl text-indigo-400 mb-2"></iconify-icon>
                            <p class="text-[9px] font-black text-slate-500 uppercase">Attach Product Image</p>
                        </div>

                        <div id="previewContainer" class="hidden relative z-20">
                            <img id="imgPreview" src="#" class="w-full h-32 object-cover rounded-xl shadow-lg border-2 border-white">
                            <button type="button" onclick="resetImage()" class="absolute -top-2 -right-2 bg-rose-500 text-white w-6 h-6 rounded-full shadow-lg flex items-center justify-center text-xs font-black hover:bg-slate-900 transition-all">×</button>
                        </div>
                    </div>
                </div>

                <div class="status-glass-mini relative overflow-hidden">
                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_#34d399]"></div>
                        <div>
                            <h5 class="font-black text-sm text-white">Online Ready</h5>
                            <p class="text-[9px] text-indigo-100/70 font-medium">Auto-sync with storefront</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const imgInput = document.getElementById('imgInput');
    const imgPreview = document.getElementById('imgPreview');
    const previewContainer = document.getElementById('previewContainer');
    const placeholderView = document.getElementById('placeholderView');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            previewContainer.classList.remove('hidden');
            placeholderView.classList.add('hidden');
        }
    }

    function resetImage() {
        imgInput.value = "";
        previewContainer.classList.add('hidden');
        placeholderView.classList.remove('hidden');
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>