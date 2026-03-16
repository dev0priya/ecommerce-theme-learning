<?php
// 1. Load the engine
require 'include/load.php';

/* ================================
   PAGINATION & SEARCH LOGIC (Project A Logic Preserved)
================================ */
$limit = 12; 
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
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

// Include updated Head and Header
include 'partials/header.php'; 
?>

<div class="dashboard-main-body">
    
    <div class="card mb-6 border-0 shadow-sm">
        <div class="card-body p-6">
            <form action="index.php" method="GET" class="flex items-center gap-3">
                <div class="relative grow">
                    <input type="text" name="q" placeholder="Search products..." value="<?= e($search) ?>" 
                           class="form-control ps-11 bg-neutral-50 dark:bg-neutral-800 border-neutral-200 dark:border-neutral-700 rounded-lg py-3">
                    <iconify-icon icon="ion:search-outline" class="absolute start-4 top-1/2 -translate-y-1/2 text-xl text-secondary-light"></iconify-icon>
                </div>
                <button type="submit" class="btn btn-primary-600 px-8 py-3 rounded-lg font-semibold transition-all">Search</button>
                <?php if ($search): ?>
                    <a href="index.php" class="text-danger-600 font-medium hover:underline">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:col-span-3 gap-6">
        <?php if ($products): ?>
            <?php foreach ($products as $p): ?>
                <div class="card h-full rounded-xl border-0 shadow-sm hover:shadow-md transition-all group overflow-hidden bg-white dark:bg-neutral-700">
                    <div class="relative overflow-hidden aspect-[4/3] bg-neutral-100">
                        <img src="assets/uploads/<?= e($p['image']) ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                             <button onclick="addToCart(<?= $p['id'] ?>)" class="w-10 h-10 bg-white text-primary-600 rounded-full flex items-center justify-center shadow-lg hover:bg-primary-600 hover:text-white transition-all">
                                <iconify-icon icon="majesticons:shopping-cart" class="text-xl"></iconify-icon>
                             </button>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <h6 class="text-lg font-bold text-neutral-900 dark:text-white mb-1 truncate"><?= e($p['product_name']) ?></h6>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xl font-bold text-primary-600">$<?= number_format($p['price'], 2) ?></span>
                            <span class="text-xs font-medium text-secondary-light bg-neutral-100 dark:bg-neutral-600 px-2 py-1 rounded">In Stock</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full py-20 text-center">
                <iconify-icon icon="line-md:search-list-twotone" class="text-6xl text-neutral-300 mb-4"></iconify-icon>
                <p class="text-secondary-light text-lg">No products found matching your search.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="flex justify-center mt-10">
        <nav aria-label="Page navigation">
            <ul class="flex items-center gap-2">
                <?php if ($page > 1): ?>
                    <li>
                        <a href="index.php?page=<?= $page - 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-lg border border-neutral-200 dark:border-neutral-700 text-secondary-light hover:bg-primary-600 hover:text-white transition-all">
                            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="px-4 py-2 bg-primary-50 dark:bg-primary-600/20 text-primary-600 rounded-lg font-bold">
                    Page <?= $page ?> of <?= $total_pages ?>
                </li>

                <?php if ($page < $total_pages): ?>
                    <li>
                        <a href="index.php?page=<?= $page + 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-lg border border-neutral-200 dark:border-neutral-700 text-secondary-light hover:bg-primary-600 hover:text-white transition-all">
                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>

</div>

<script>
function addToCart(productId) {
    fetch('api/cart/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Use a nice toast if Project B has one, else alert
            alert('Added to cart!');
            location.reload();
        }
    });
}
</script>

<?php include 'partials/footer.php'; ?>