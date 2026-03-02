<?php
require '../include/load.php';
checkLogin();

// 1. Fetch all products
$stmt = $pdo->query("
    SELECT 
        products.*, 
        categories.name AS category 
    FROM products
    JOIN categories ON products.category_id = categories.id
    ORDER BY products.created_at DESC
");

$products = $stmt->fetchAll();

include '../partials/head.php';
?>

<body>
    <?php include '../partials/sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Manage Products</h1>
            <a href="add.php" style="background: green; color: white; padding: 10px; text-decoration: none;">
                + Add New Product
            </a>
        </div>

        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr style="background: #eee;">
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= e($p['id']) ?></td>
                    <td>
                     <?php if (!empty($p['image'])): ?>
                    <img src="../assets/uploads/<?= e($p['image']) ?>"
             alt="Product Image"
             style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
    <?php else: ?>
        <span>No Image</span>
    <?php endif; ?>
</td>
                    <td><?= e($p['product_name']) ?></td>
                    <td><?= e($p['category']) ?></td>
                    <td><?= e($p['price']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>">Edit</a>
                        |
                        <a href="#" onclick="deleteItem(<?= $p['id'] ?>, 'products')" style="color:red;">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/app.js"></script>
    

</body>
</html>
