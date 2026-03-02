<?php
require '../../include/load.php';

// API returns JSON only
header('Content-Type: application/json');

// Read JSON input sent by JavaScript
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if ($id) {

    // 1️⃣ Create cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // 2️⃣ If product already in cart → increase quantity
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } 
    // 3️⃣ Else → add product with quantity 1
    else {
        $_SESSION['cart'][$id] = 1;
    }

    echo json_encode([
        'status' => 'success',
        'cart_count' => count($_SESSION['cart'])
    ]);

} else {
    echo json_encode([
        'status' => 'error'
    ]);
}

