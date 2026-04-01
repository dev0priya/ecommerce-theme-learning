<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'All Products';
$subTitle = 'Product Management';

$script = "<script>
    if (document.getElementById('selection-table') && typeof simpleDatatables.DataTable !== 'undefined') {
        new simpleDatatables.DataTable('#selection-table', {
            columns: [{ select: [0, 4], sortable: false }]
        });
    }
</script>";

$stmt = $pdo->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();

include '../partials/layouts/layoutTop.php'; 
?>

<div class="dashboard-main-body">
    <?php include '../partials/breadcrumb.php'; ?>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white dark:bg-neutral-900">
                <div class="card-header flex justify-between items-center p-6 border-b border-neutral-100 dark:border-neutral-700">
                    <h6 class="text-xl font-bold text-neutral-800 dark:text-white mb-0">Product List</h6>
                    <a href="add.php" class="btn btn-primary-600 px-6 py-2.5 rounded-lg flex items-center gap-2 text-sm font-semibold text-white">
                        <iconify-icon icon="lucide:plus" class="text-lg"></iconify-icon>
                        Add New Product
                    </a>
                </div>
                
                <div class="card-body p-6">
                    <table id="selection-table" class="border border-neutral-200 dark:border-neutral-700 rounded-lg border-separate w-full">
                        <thead>
                            <tr class="bg-neutral-50 dark:bg-neutral-800">
                                <th scope="col" class="p-4 text-neutral-800 dark:text-white font-bold text-sm">S.L</th>
                                <th scope="col" class="p-4 text-neutral-800 dark:text-white font-bold text-sm">Product Details</th>
                                <th scope="col" class="p-4 text-neutral-800 dark:text-white font-bold text-sm">Category</th>
                                <th scope="col" class="p-4 text-neutral-800 dark:text-white font-bold text-sm">Price</th>
                                <th scope="col" class="p-4 text-neutral-800 dark:text-white font-bold text-sm text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sl = 1;
                            foreach ($products as $row): 
                                $imgName = !empty($row['image']) ? $row['image'] : 'default-product.png';
                                $imgPath = "../assets/uploads/" . $imgName;
                            ?>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-all border-b border-neutral-100 dark:border-neutral-700">
                                <td class="p-4 text-sm text-neutral-600 dark:text-neutral-400"><?= $sl++; ?></td>
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <img src="<?= $imgPath ?>" alt="" class="w-10 h-10 shrink-0 me-3 rounded-lg object-cover border border-neutral-100 dark:border-neutral-700">
                                        <div class="grow">
                                            <h6 class="text-sm mb-0 font-bold text-neutral-800 dark:text-white"><?= e($row['product_name']) ?></h6>
                                            <span class="text-xs text-primary-600 font-medium">#PROD-<?= $row['id'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-neutral-600 dark:text-neutral-400"><?= e($row['cat_name'] ?? 'General') ?></td>
                                <td class="p-4 text-sm font-bold text-neutral-800 dark:text-white">$<?= number_format($row['price'], 2) ?></td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="w-8 h-8 bg-success-100 text-success-600 rounded-full inline-flex items-center justify-center">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')" class="w-8 h-8 bg-danger-100 text-danger-600 rounded-full inline-flex items-center justify-center">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
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

<?php include '../partials/layouts/layoutBottom.php'; ?>
