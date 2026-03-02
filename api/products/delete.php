<?php
require '../../include/load.php';

// JSON only
header('Content-Type: application/json');

// Security check
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if (!$id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No product ID provided'
    ]);
    exit;
}

// 1️⃣ Get product image filename BEFORE deleting product
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Product not found'
    ]);
    exit;
}

// 2️⃣ Delete product from database
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

// 3️⃣ Delete image file from folder (if exists)
if (!empty($product['image'])) {
    $imagePath = '../../assets/uploads/' . $product['image'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// 4️⃣ Success response
echo json_encode([
    'status' => 'success'
]);
