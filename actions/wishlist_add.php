<?php
// session_start sabse pehle hona chahiye
session_start();

// Include your database connection
require_once '../include/load.php'; 

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Agar session nahi mil raha toh ye error bhejega
    echo json_encode(['status' => 'error', 'message' => 'session_not_found']);
    exit;
}

if (isset($_POST['product_id'])) {
    $p_id = $_POST['product_id'];
    $u_id = $_SESSION['user_id'];

    // Check if already exists
    $check = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $check->execute([$u_id, $p_id]);

    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        if ($stmt->execute([$u_id, $p_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist!']);
        }
    } else {
        echo json_encode(['status' => 'exists', 'message' => 'Already in wishlist']);
    }
}
?>