<?php
require '../include/load.php';
checkLogin();

// Fetch categories for dropdown
$categories = $pdo->query(
    "SELECT * FROM categories ORDER BY name ASC"
)->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $category_id  = $_POST['category_id'];

    // Validate basic fields
    if ($product_name === '' || !is_numeric($price)) {
        $error = "Please enter valid product details.";
    }

    // Attempt image upload using centralized helper
    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $imageName = uploadImage($_FILES['image'], 'uploads');

        if (!$imageName) {
            $error = "Upload failed. Please upload a valid image (JPG, PNG, WEBP).";
        }
    }

    // Save product if no errors
    if (empty($error)) {
        $stmt = $pdo->prepare(
            "INSERT INTO products (product_name, price, category_id, image)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $product_name,
            $price,
            $category_id,
            $imageName
        ]);

        redirect('index.php');
    }
}

include '../partials/head.php';
?>

<body>
<?php include '../partials/sidebar.php'; ?>

<div class="content">
    <h2>Add New Product</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Product Name:</label><br>
        <input type="text" name="product_name" required><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= e($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Product Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit">Save Product</button>

    </form>
</div>

</body>
</html>
