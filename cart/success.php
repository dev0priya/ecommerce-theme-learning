<?php
session_start();
require '../include/load.php';
checkLogin();

$tid = $_GET['tid'] ?? null;

// ❌ invalid access
if (!$tid || empty($_SESSION['cart'])) {
    die("Payment verification failed or cart empty.");
}

try {

    /* =========================
       1. VERIFY PAYMENT
    ========================= */
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    $intent = \Stripe\PaymentIntent::retrieve($tid);

    if ($intent->status !== 'succeeded') {
        die("Payment not successful");
    }

    /* =========================
       2. START TRANSACTION
    ========================= */
    $pdo->beginTransaction();

    $total = 0;
    $items = [];

    /* =========================
       3. FETCH CART PRODUCTS
    ========================= */
    foreach ($_SESSION['cart'] as $pid => $qty) {

        $stmt = $pdo->prepare("SELECT product_name, price FROM products WHERE id = ?");
        $stmt->execute([$pid]);
        $product = $stmt->fetch();

        if ($product) {
            $itemTotal = $product['price'] * $qty;
            $total += $itemTotal;

            $items[] = [
                'id' => $pid,
                'name' => $product['product_name'],
                'qty' => $qty,
                'price' => $product['price']
            ];
        }
    }

    /* =========================
       4. CREATE ORDER
    ========================= */
    $stmtOrder = $pdo->prepare(
        "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Paid')"
    );
    $stmtOrder->execute([$_SESSION['user_id'], $total]);

    $order_id = $pdo->lastInsertId();

    /* =========================
       5. ORDER ITEMS
    ========================= */
    $stmtItem = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
    );

    foreach ($items as $item) {
        $stmtItem->execute([
            $order_id,
            $item['id'],
            $item['qty'],
            $item['price']
        ]);
    }

    /* =========================
       6. CREATE INVOICE
    ========================= */
    $invoiceNum = "INV-" . date('Y') . "-" . str_pad($order_id, 4, "0", STR_PAD_LEFT);
    $tax = $total * 0.10;
    $grandTotal = $total + $tax;

    $stmtInv = $pdo->prepare(
        "INSERT INTO invoices 
        (invoice_number, order_id, customer_id, subtotal, tax_total, grand_total, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmtInv->execute([
        $invoiceNum,
        $order_id,
        $_SESSION['user_id'],
        $total,
        $tax,
        $grandTotal,
        'paid'
    ]);

    $invoiceId = $pdo->lastInsertId();

    /* =========================
       7. INVOICE ITEMS
    ========================= */
    $stmtInvItem = $pdo->prepare(
        "INSERT INTO invoice_items 
        (invoice_id, product_name, quantity, price_per_unit, total_price) 
        VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($items as $item) {
        $stmtInvItem->execute([
            $invoiceId,
            $item['name'],
            $item['qty'],
            $item['price'],
            ($item['qty'] * $item['price'])
        ]);
    }

    /* =========================
       8. COMMIT
    ========================= */
    $pdo->commit();

    /* =========================
       9. CLEAR CART
    ========================= */
    unset($_SESSION['cart']);

    /* =========================
       10. REDIRECT WITH DELAY (PREMIUM UX)
    ========================= */
    echo "
    <html>
    <head>
        <style>
            body {
                display:flex;
                justify-content:center;
                align-items:center;
                height:100vh;
                font-family: Arial;
                background:#f8fafc;
            }
            .loader {
                text-align:center;
            }
            .spinner {
                width:60px;
                height:60px;
                border:5px solid #ddd;
                border-top:5px solid #6366f1;
                border-radius:50%;
                animation: spin 1s linear infinite;
                margin:auto;
            }
            @keyframes spin {
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <div class='loader'>
            <div class='spinner'></div>
            <h3>Processing your order...</h3>
        </div>

        <script>
            setTimeout(function(){
                window.location.href = 'order-success.php?id={$invoiceId}';
            }, 1500);
        </script>
    </body>
    </html>
    ";

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die("Error: " . $e->getMessage());
}