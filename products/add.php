<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'Create Masterpiece';
$subTitle = 'E-Commerce / Inventory';

// FORM LOGIC
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

/* ✅ ONLY THIS (HEADER + SIDEBAR FROM LAYOUT) */
include '../partials/layouts/layoutTop.php'; 
?>

<link rel="stylesheet" href="../assets/css/pages/product-style.css">

<div class="dashboard-main-body premium-gradient-bg px-10 py-12">

    <form action="add.php" method="POST" enctype="multipart/form-data">
        
        <!-- HEADER SECTION -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.4em] mb-1 block">
                    Creation Mode
                </span>

                <h1 class="text-4xl font-black bg-clip-text text-transparent 
                    bg-gradient-to-r from-indigo-600 to-purple-600 tracking-tight">
                    New Masterpiece
                </h1>

                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] mt-2 ml-1">
                    E-Commerce Catalog • 2026
                </p>
            </div>

            <div class="flex items-center gap-6">
                <a href="index.php" 
                   class="text-slate-400 font-black text-sm hover:text-red-500 transition-colors">
                    Discard
                </a>

                <button type="submit" class="btn-pop flex items-center gap-3 shadow-2xl">
                    <iconify-icon icon="solar:magic-stick-3-bold-duotone" class="text-2xl"></iconify-icon>
                    Create Product
                </button>
            </div>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-12 gap-10">

            <!-- LEFT -->
            <div class="col-span-12 lg:col-span-8">
                <div class="vibrant-card p-10 border-t-4 border-indigo-600">

                    <h4 class="text-xl font-black text-slate-800 mb-10 flex items-center gap-3">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                        Core Information
                    </h4>

                    <div class="space-y-8">

                        <!-- PRODUCT NAME -->
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">
                                Product Title
                            </label>

                            <input type="text" name="product_name"
                                   class="pop-input w-full text-lg"
                                   placeholder="e.g. Premium Leather Jacket" required>
                        </div>

                        <!-- PRICE + CATEGORY -->
                        <div class="grid grid-cols-2 gap-8">

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">
                                    Price ($)
                                </label>

                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-indigo-600 font-black">$</span>

                                    <input type="number" step="0.01" name="price"
                                           class="pop-input w-full pl-10 font-black text-slate-800 text-2xl"
                                           placeholder="0.00" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">
                                    Category
                                </label>

                                <select name="category_id"
                                        class="pop-input w-full font-bold cursor-pointer appearance-none" required>

                                    <option value="">Select Category</option>

                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>">
                                            <?= e($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-span-12 lg:col-span-4 space-y-8">

                <!-- IMAGE -->
                <div class="vibrant-card p-8">

                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">
                        Product Visual
                    </h4>

                    <div class="upload-vibrant relative p-10 text-center group cursor-pointer overflow-hidden rounded-3xl shadow-inner">

                        <input type="file" name="image" id="imgInput"
                               class="absolute inset-0 opacity-0 z-30 cursor-pointer" accept="image/*">

                        <div id="placeholderView" class="py-10">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-all duration-500">
                                <iconify-icon icon="solar:gallery-add-bold-duotone" class="text-3xl text-indigo-600"></iconify-icon>
                            </div>
                            <p class="text-[11px] font-black text-slate-700 uppercase">Drop Image Here</p>
                        </div>

                        <div id="previewContainer" class="hidden relative z-20">
                            <img id="imgPreview" src="#"
                                 class="w-full h-56 object-cover rounded-2xl shadow-2xl border-4 border-white">

                            <button type="button" onclick="resetImage()"
                                    class="absolute -top-3 -right-3 bg-white text-red-500 w-8 h-8 rounded-full shadow-2xl flex items-center justify-center font-black hover:bg-red-500 hover:text-white transition-all">
                                ×
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STATUS -->
                <div class="status-glass shadow-indigo-200 shadow-2xl">
                    <div class="relative z-10">
                        <h5 class="font-black text-lg text-white">Online Ready</h5>
                        <p class="text-[10px] text-indigo-200/60 mt-2 font-medium">
                            Auto-sync with global storefront enabled.
                        </p>
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