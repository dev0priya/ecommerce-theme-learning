<?php
require '../include/load.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit(); }

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { die("Product not found."); }

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$error = '';
$title = 'Refine Product';
$subTitle = 'Management / Editor';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $category_id  = $_POST['category_id'];
    $imageName    = $product['image']; 

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image_name);
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("UPDATE products SET product_name=?, price=?, category_id=?, image=? WHERE id=?");
        $stmt->execute([$product_name, $price, $category_id, $imageName, $id]);
        header('Location: index.php');
        exit();
    }
}

include '../partials/layouts/layoutTop.php'; 
?>

<link rel="stylesheet" href="../assets/css/pages/product-style.css">

<style>
    .edit-gradient-bg {
        background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        min-height: 100vh;
    }
    .image-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 900;
        color: #4f46e5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="dashboard-main-body px-10 py-12 edit-gradient-bg">
    <form method="POST" enctype="multipart/form-data">
        
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.4em] mb-1 block">Editor Mode</span>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">
                    Refine <span class="text-indigo-600"><?= e($product['product_name']) ?></span>
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="index.php" class="px-6 py-3 text-slate-400 font-bold hover:text-red-500 transition-all">Discard</a>
                <button type="submit" class="btn-pop px-10 py-4 shadow-2xl">
                    <iconify-icon icon="solar:refresh-circle-bold-duotone" class="text-2xl"></iconify-icon>
                    Update Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <div class="col-span-12 lg:col-span-8">
                <div class="vibrant-card p-10 border-t-4 border-indigo-600">
                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Display Name</label>
                            <input type="text" name="product_name" class="pop-input w-full text-xl" value="<?= e($product['product_name']) ?>" required>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Pricing (USD)</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-indigo-600 font-black">$</span>
                                    <input type="number" step="0.01" name="price" class="pop-input w-full pl-10 font-black text-slate-800 text-2xl" value="<?= e($product['price']) ?>" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Collection</label>
                                <select name="category_id" class="pop-input w-full font-bold cursor-pointer" required>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                            <?= e($c['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-8">
                <div class="vibrant-card p-6 overflow-hidden">
                    <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Visual Asset</h4>
                    <div class="relative group rounded-3xl overflow-hidden shadow-2xl">
                        <input type="file" name="image" id="imgInput" class="absolute inset-0 opacity-0 z-30 cursor-pointer" accept="image/*">
                        <?php 
                            $img = !empty($product['image']) ? $product['image'] : 'default.png';
                            $path = "../assets/uploads/" . $img;
                        ?>
                        <img id="imgPreview" src="<?= $path ?>" class="w-full h-72 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="image-badge">CURRENT ASSET</div>
                        
                        <div class="absolute inset-0 bg-indigo-600/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                            <div class="text-center text-white">
                                <iconify-icon icon="solar:camera-bold" class="text-4xl mb-2"></iconify-icon>
                                <p class="text-xs font-black uppercase">Replace Image</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="status-glass shadow-indigo-200 shadow-2xl">
                    <div class="relative z-10">
                        <h5 class="text-[10px] font-black uppercase text-indigo-300 mb-6 tracking-widest">System Insights</h5>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-indigo-400/20 pb-2">
                                <span class="text-xs text-indigo-200">Database ID</span>
                                <span class="font-black text-white">#<?= $id ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-indigo-400/20 pb-2">
                                <span class="text-xs text-indigo-200">Status</span>
                                <span class="flex items-center gap-2 font-black text-green-400">
                                    <div class="accent-dot"></div> Live
                                </span>
                            </div>
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

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
        }
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>