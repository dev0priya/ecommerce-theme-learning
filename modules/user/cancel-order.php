<?php
require '../../include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('../../dashboard.php');
}

$orderId = $_POST['order_id'] ?? null;
$userId = $_SESSION['user_id'];

if ($orderId) {

    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'Canceled'
        WHERE id = ? AND user_id = ? AND status = 'Pending'
    ");

    $stmt->execute([$orderId, $userId]);
}

redirect('dashboard.php');