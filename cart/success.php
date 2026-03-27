<?php
require '../include/load.php';
checkLogin();

$tid = $_GET['tid'] ?? null;

if (!$tid || empty($_SESSION['cart'])) {
    echo "Payment failed or cart is empty.";
    exit;
}

// 1. Verify payment with Stripe
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
$intent = \Stripe\PaymentIntent::retrieve($tid);

if ($intent->status !== 'succeeded') {
    echo "Payment not successful.";
    exit;
}

// 2. Calculate total
$total = 0;
$items = [];
foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT product_name, price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if ($product) {
        $total += ($product['price'] * $qty);
        $items[] = ['id' => $pid, 'name' => $product['product_name'], 'qty' => $qty, 'price' => $product['price']];
    }
}

// 3. Save order + Invoice
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmtItem->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
    }

    // --- NEW: Generate Invoice ---
    $invoiceNum = "INV-" . date('Y') . "-" . str_pad($order_id, 4, "0", STR_PAD_LEFT);
    $tax = $total * 0.10;
    $grandTotal = $total + $tax;

    $stmtInv = $pdo->prepare("INSERT INTO invoices (invoice_number, order_id, customer_id, subtotal, tax_total, grand_total, status) VALUES (?, ?, ?, ?, ?, ?, 'paid')");
    $stmtInv->execute([$invoiceNum, $order_id, $_SESSION['user_id'], $total, $tax, $grandTotal]);
    $invoiceId = $pdo->lastInsertId();

    $stmtInvItem = $pdo->prepare("INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmtInvItem->execute([$invoiceId, $item['name'], $item['qty'], $item['price'], ($item['qty'] * $item['price'])]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);

    // --- REDIRECT TO NEW SUCCESS PAGE ---
    header("Location: order-success.php?id=" . $invoiceId);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Order saving failed.";
    exit;
}
?>