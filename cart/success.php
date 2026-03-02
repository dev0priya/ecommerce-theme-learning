<?php
require '../include/load.php';
checkLogin();

$tid = $_GET['tid'] ?? null;

if (!$tid || empty($_SESSION['cart'])) {
    echo "Payment failed or cart is empty.";
    exit;
}

// 1. Verify payment with Stripe (server-side)
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$intent = \Stripe\PaymentIntent::retrieve($tid);

if ($intent->status !== 'succeeded') {
    echo "Payment not successful.";
    exit;
}

// 2. Calculate total again (trust DB, not session)
$total = 0;
$items = [];

foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT product_name, price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if ($product) {
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;

        $items[] = [
            'id'    => $pid,
            'qty'   => $qty,
            'price' => $product['price'],
        ];
    }
}

// 3. Save order + items using transaction (ACID)
$pdo->beginTransaction();

try {
    // Insert order
    $stmt = $pdo->prepare(
        "INSERT INTO orders (user_id, total_amount, status)
         VALUES (?, ?, 'Pending')"
    );
    $stmt->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    $stmtItem = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    foreach ($items as $item) {
        $stmtItem->execute([
            $order_id,
            $item['id'],
            $item['qty'],
            $item['price']
        ]);
    }

    $pdo->commit();

    // Clear cart
    unset($_SESSION['cart']);
    // Send email receipt
if (isset($_SESSION['user_email'])) {

    $message = "
        <h1>Order Receipt</h1>
        <p>Thank you for your purchase!</p>
        <p><strong>Transaction ID:</strong> {$tid}</p>
        <p><strong>Total Paid:</strong> $" . number_format($total, 2) . "</p>
        <p>Your order is now being processed.</p>
    ";

    sendEmail(
        $_SESSION['user_email'],
        'Your Order Receipt - My Shop',
        $message
    );
}


} catch (Exception $e) {
    $pdo->rollBack();
    echo "Order saving failed.";
    exit;
}
?>

<h1>✅ Payment Successful!</h1>
<p>Your order has been placed successfully.</p>
<p><strong>Transaction ID:</strong> <?= e($tid) ?></p>

<a href="../index.php">Go back to shop</a>
