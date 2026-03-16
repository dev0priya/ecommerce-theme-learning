<?php
require '../include/load.php';
checkLogin(); // Ensure only admins are here

// Project A Logic: Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();

include '../partials/header.php'; 
?>

<div class="flex min-h-screen">
    <?php include '../partials/sidebar-admin.php'; ?>

    <main class="dashboard-main flex-grow-1">
        <div class="dashboard-main-body p-6">
            
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h6 class="text-2xl font-bold text-neutral-900 dark:text-white mb-0">Product Management</h6>
                <a href="add-product.php" class="btn btn-primary-600 px-6 py-2 rounded-lg flex items-center gap-2">
                    <iconify-icon icon="lucide:plus" class="text-xl"></iconify-icon>
                    Add New Product
                </a>
            </div>

            <div class="card h-full border-0 shadow-sm rounded-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-6">Product Details</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Stock Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="ps-6">
                                        <div class="flex items-center">
                                            <img src="../assets/uploads/<?= e($p['image']) ?>" alt="" class="w-12 h-12 shrink-0 me-3 rounded-lg object-cover border border-neutral-100">
                                            <div class="grow">
                                                <h6 class="text-base mb-0 font-semibold text-neutral-800 dark:text-white"><?= e($p['product_name']) ?></h6>
                                                <span class="text-xs text-secondary-light">ID: #<?= $p['id'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-sm font-medium">Fashion</span></td> <td><span class="text-lg font-bold text-primary-600">$<?= number_format($p['price'], 2) ?></span></td>
                                    <td>
    <?php 
        // 1. Rename 'stock' to match your actual DB column (e.g., 'quantity')
        // If your column is 'qty', change $p['stock'] to $p['qty'] below:
        $currentStock = $p['quantity'] ?? $p['qty'] ?? 0; 

        // 2. Apply Project B Status Badges
        if ($currentStock <= 0) {
            echo '<span class="bg-danger-100 text-danger-600 px-3 py-1 rounded-full text-xs font-bold">Out of Stock</span>';
        } elseif ($currentStock < 10) {
            echo '<span class="bg-warning-100 text-warning-600 px-3 py-1 rounded-full text-xs font-bold">Low Stock ('.$currentStock.')</span>';
        } else {
            echo '<span class="bg-success-100 text-success-600 px-3 py-1 rounded-full text-xs font-bold">Available ('.$currentStock.')</span>';
        }
    ?>
</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="edit.php?id=<?= $p['id'] ?>" class="w-8 h-8 bg-info-100 text-info-600 rounded-lg flex items-center justify-center hover:bg-info-600 hover:text-white transition-all">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                            <a href="delete.php?id=<?= $p['id'] ?>" class="w-8 h-8 bg-danger-100 text-danger-600 rounded-lg flex items-center justify-center hover:bg-danger-600 hover:text-white transition-all" onclick="return confirm('Delete this product?')">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
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
        <?php include '../partials/footer.php'; ?>
    </main>
</div>