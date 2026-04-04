<?php
session_start();
require_once '../include/load.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$p_id = $_POST['product_id'];
$u_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id=? AND product_id=?");
$stmt->execute([$u_id, $p_id]);

echo json_encode(['status' => 'removed']);