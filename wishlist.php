<?php
require 'include/load.php';
include 'partials/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='sign-in.php'</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT products.*
    FROM wishlist
    JOIN products ON products.id = wishlist.product_id
    WHERE wishlist.user_id = ?
    ORDER BY wishlist.id DESC
");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --accent: #f43f5e;
}

.wishlist-container {
    max-width: 1300px;
    margin: 50px auto;
    padding: 0 20px;
}

.wishlist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.wishlist-header h2 {
    font-size: 30px;
    font-weight: 900;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.wishlist-header span {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: 6px 12px;
    border-radius: 999px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 30px;
}

.card {
    background: white;
    border-radius: 25px;
    padding: 12px;
    transition: 0.4s;
    border: 1px solid #e5e7eb;
    position: relative;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(99,102,241,0.15);
}

.img-box {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
}

.img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.5s;
}

.card:hover img {
    transform: scale(1.08);
}

.remove-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid #eee;
    transition: 0.3s;
}

.remove-btn:hover {
    background: var(--accent);
}

.remove-btn iconify-icon {
    font-size: 20px;
    color: #64748b;
}

.remove-btn:hover iconify-icon {
    color: white;
}

.content {
    padding: 15px 5px;
    text-align: center;
}

.name {
    font-weight: 700;
    margin-bottom: 5px;
}

.price {
    color: var(--primary);
    font-weight: 800;
    font-size: 18px;
    margin-bottom: 10px;
}

.add-btn {
    width: 100%;
    padding: 10px;
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    font-weight: 600;
    cursor: pointer;
}

.empty {
    text-align: center;
    padding: 80px 20px;
}

.empty iconify-icon {
    font-size: 60px;
    color: #cbd5f5;
}

.empty p {
    margin-top: 15px;
    color: #64748b;
}
</style>

<div class="wishlist-container">

    <div class="wishlist-header">
        <h2>My Wishlist ❤️</h2>
        <span><?= count($products) ?> Items</span>
    </div>

    <?php if (count($products) == 0): ?>

        <div class="empty">
            <iconify-icon icon="solar:heart-broken"></iconify-icon>
            <p>Your wishlist is empty</p>
        </div>

    <?php else: ?>

    <div class="grid">
        <?php foreach ($products as $p): ?>

            <div class="card">

                <div class="remove-btn">
                    <iconify-icon icon="solar:trash-bin-trash"></iconify-icon>
                </div>

                <div class="img-box">
                    <img src="assets/uploads/<?= $p['image'] ?>">
                </div>

                <div class="content">
                    <div class="name"><?= $p['product_name'] ?></div>
                    <div class="price">₹<?= $p['price'] ?></div>

                    <button class="add-btn">
                        Add to Cart
                    </button>
                </div>

            </div>

        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</div>

<?php include 'partials/footer.php'; ?>
