<?php 
require '../include/load.php'; 
checkLogin(); 

$title = 'All Products';
$subTitle = 'Product Management';

// Datatable Initialization Script
$script = "<script>
    if (document.getElementById('selection-table') && typeof simpleDatatables.DataTable !== 'undefined') {
        new simpleDatatables.DataTable('#selection-table', {
            columns: [{ select: [0, 4], sortable: false }]
        });
    }
</script>";

// Fetching products with Category Name
$stmt = $pdo->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();

include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Premium Hover Fix for Dark & Light Theme */
    .datatable-table > tbody > tr:hover {
        background-color: rgba(0, 0, 0, 0.05) !important; /* Light Theme Hover */
    }

    .dark .datatable-table > tbody > tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important; /* Dark Theme Hover */
    }

    .dark .datatable-table > tbody > tr:hover td {
        color: #fff !important; /* Force white text on hover in dark mode */
    }

    /* Selection Table Specific Fixes */
    .dark #selection-table tr.active, 
    .dark #selection-table tr:hover {
        background-color: #1e293b !important; 
    }

    .dark .datatable-pagination a {
        color: #9ca3af !important;
    }

    /* ✅ Action Buttons Fix for Dark Theme */
    .dark .action-btn-edit {
        background-color: #1e293b !important; /* Dark Slate background for Edit in Dark Mode */
        color: #4ade80 !important; /* Bright Success Icon Color */
        border: 1px solid rgba(74, 222, 128, 0.2) !important; /* Subtle Success Border */
    }
    
    .dark .action-btn-edit:hover {
        background-color: #166534 !important; /* Darker Success on Hover */
        color: #fff !important;
    }

    .dark .action-btn-delete {
        background-color: #1e293b !important; /* Dark Slate background for Delete in Dark Mode */
        color: #f87171 !important; /* Bright Danger Icon Color */
        border: 1px solid rgba(248, 113, 113, 0.2) !important; /* Subtle Danger Border */
    }

    .dark .action-btn-delete:hover {
        background-color: #991b1b !important; /* Darker Danger on Hover */
        color: #fff !important;
    }
</style>

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
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn-edit w-8 h-8 bg-success-100 text-success-600 rounded-full inline-flex items-center justify-center transition-transform hover:scale-110">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <button type="button" 
                                                onclick="deleteProduct(<?= $row['id'] ?>, this)" 
                                                class="action-btn-delete w-8 h-8 bg-danger-100 text-danger-600 rounded-full inline-flex items-center justify-center border-0 cursor-pointer transition-all hover:bg-danger-600 hover:text-white hover:scale-110">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
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

<script>
function deleteProduct(id, button) {
    if (confirm('Are you sure you want to delete this product?')) {
        const apiPath = '../api/products/delete.php';

        fetch(apiPath, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : null;

            if (!response.ok) {
                throw new Error(data?.message || 'Delete API not found at ' + apiPath);
            }
            return data;
        })
        .then(data => {
            if (data && data.status === 'success') {
                const row = button.closest('tr');
                row.style.transition = '0.4s ease-out';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => row.remove(), 400);
            } else {
                alert('Action Failed: ' + (data?.message || 'Unexpected server error'));
            }
        })
        .catch(error => {
            console.error('Delete Error:', error);
            alert('System Error: Could not reach the deletion file.\nPath tried: ' + apiPath);
        });
    }
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>