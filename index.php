<?php
// 1. Load the engine
require 'include/load.php';

$limit = 12; 
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['q'] ?? '';

if ($search) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE product_name LIKE ?");
    $countStmt->execute(["%$search%"]);
    $total_items = $countStmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_name LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_items = $countStmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$products = $stmt->fetchAll();
$total_pages = ceil($total_items / $limit);

include 'partials/header.php'; 
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-purple: #a855f7;
        --pop-emerald: #10b981;
        --pop-rose: #f43f5e; /* Wishlist Color */
        --slate-900: #0f172a;
        --bg-soft: #fbfdff;
    }

    body { background: var(--bg-soft); font-family: 'Inter', sans-serif; }
    .dashboard-main-body { padding: 40px; }

    /* Product Card Pop Style */
    .product-card {
        background: white;
        border-radius: 2.3rem;
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.12);
    }

    .img-container {
        aspect-ratio: 1/1;
        border-radius: 1.8rem;
        margin: 10px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        position: relative;
    }

    /* WISHLIST ICON STYLE */
    .wishlist-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 42px;
        height: 42px;
        background: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        z-index: 10;
    }
    .wishlist-btn:hover {
        color: var(--pop-rose);
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(244, 63, 94, 0.2);
    }
    .wishlist-btn.active {
        color: var(--pop-rose);
    }

    .btn-cart-pop {
        background: linear-gradient(135deg, var(--pop-indigo) 0%, var(--pop-purple) 100%);
        color: white;
        padding: 12px;
        border-radius: 14px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);
    }
    .btn-cart-pop:hover {
        filter: brightness(1.1);
        box-shadow: 0 12px 20px rgba(99, 102, 241, 0.3);
    }
</style>

<div class="dashboard-main-body antialiased">
    
    <div class="mb-12">
        <span class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.3em] block mb-1">Curated Collection</span>
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Premium Masterpieces</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <button class="wishlist-btn" onclick="toggleWishlist(<?= $p['id'] ?>, this)">
                    <iconify-icon icon="solar:heart-bold-duotone" class="text-2xl"></iconify-icon>
                </button>

                <div class="img-container">
                    <img src="assets/uploads/<?= e($p['image']) ?>" class="w-full h-full object-cover">
                </div>

                <div class="p-5 pt-2 flex-grow flex flex-col justify-between">
                    <div>
                        <h6 class="text-lg font-black text-slate-800 truncate mb-1"><?= e($p['product_name']) ?></h6>
                        <div class="flex justify-between items-center mb-5">
                            <span class="text-2xl font-black text-emerald-600 tracking-tight">₹<?= number_format($p['price'], 2) ?></span>
                            <span class="text-[9px] font-black uppercase text-slate-400 bg-slate-50 px-2 py-1 rounded-md">New Arrival</span>
                        </div>
                    </div>
                    
                    <button onclick="addToCart(<?= $p['id'] ?>)" class="btn-cart-pop">
                        <iconify-icon icon="solar:cart-large-minimalistic-bold-duotone" class="text-xl"></iconify-icon>
                        Add To Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleWishlist(id, btn) {
    // Frontend par heart color change karne ke liye
    btn.classList.toggle('active');
    
    // Yahan aap apna backend API call add kar sakte hain wishlist save karne ke liye
    // console.log("Added to wishlist:", id);
}

function addToCart(productId) {
    fetch('api/cart/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    });
}
</script>

<?php include 'partials/footer.php'; ?>