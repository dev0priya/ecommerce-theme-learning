<?php
require '../../include/load.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if ($id) {
    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    echo json_encode([
        'status' => 'success',
        'cart_count' => count($_SESSION['cart'])
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
?>