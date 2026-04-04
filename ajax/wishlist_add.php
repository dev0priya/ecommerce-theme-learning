<?php
session_start();
require_once '../include/load.php';
header('Content-Type: application/json');

/* =========================
   LOGIN CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'please_login'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

/* =========================
   VALIDATION
========================= */
if (!$product_id || !is_numeric($product_id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'invalid_product'
    ]);
    exit;
}

/* =========================
   CHECK PRODUCT EXISTS
========================= */
$checkProduct = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$checkProduct->execute([$product_id]);

if ($checkProduct->rowCount() == 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'product_not_found'
    ]);
    exit;
}

/* =========================
   CHECK ALREADY EXISTS
========================= */
$check = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->execute([$user_id, $product_id]);

if ($check->rowCount() > 0) {
    echo json_encode([
        'status' => 'exists',
        'message' => 'already_added'
    ]);
    exit;
}

/* =========================
   INSERT INTO WISHLIST
========================= */
$stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
$stmt->execute([$user_id, $product_id]);

echo json_encode([
    'status' => 'success',
    'message' => 'added_successfully'
]);