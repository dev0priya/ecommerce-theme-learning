<?php
require '../include/load.php';
checkLogin();

// Get product ID
$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

// Fetch categories
$categories = $pdo->query(
    "SELECT * FROM categories ORDER BY name ASC"
)->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $category_id  = $_POST['category_id'];

    $imageName = $product['image']; // keep old image

    // New image upload
    if (!empty($_FILES['image']['name'])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = $_FILES['image']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Only JPG, PNG, or WEBP images allowed.";
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('prod_', true) . '.' . $ext;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../assets/uploads/" . $imageName
            );
        }
    }

    if ($product_name === '' || !is_numeric($price)) {
        $error = "Please enter valid product details.";
    }

    if (empty($error)) {
        $stmt = $pdo->prepare(
            "UPDATE products
             SET product_name=?, price=?, category_id=?, image=?
             WHERE id=?"
        );
        $stmt->execute([
            $product_name,
            $price,
            $category_id,
            $imageName,
            $id
        ]);

        redirect('index.php');
    }
}

include '../partials/head.php';
?>

<body>
<?php include '../partials/sidebar.php'; ?>

<div class="content">
    <h2>Edit Product</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Product Name:</label><br>
        <input type="text" name="product_name"
               value="<?= e($product['product_name']) ?>" required><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price"
               value="<?= e($product['price']) ?>" required><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"
                    <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>New Product Image:</label><br>
        <input type="file" name="image" accept="image/*"><br>
        <small>Current image: <?= e($product['image'] ?? 'none') ?></small>
        <br><br>

        <button type="submit">Update Product</button>

    </form>
</div>

</body>
</html>
