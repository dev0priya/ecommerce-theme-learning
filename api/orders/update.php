<?php
require '../../include/load.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $order_id = $_POST['order_id'] ?? null;
    $status   = $_POST['status'] ?? null;

    if (!$order_id || !$status) {
        redirect('../../orders/index.php');
    }

    // Update order status
    $stmt = $pdo->prepare(
        "UPDATE orders SET status = ? WHERE id = ?"
    );
    $stmt->execute([$status, $order_id]);

    // Redirect back to order view
    header('Location: ../../orders/view.php?id=' . $order_id);
    exit;
}
