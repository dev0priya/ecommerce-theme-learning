<?php
require '../include/load.php';
checkLogin();

$tid = $_GET['tid'] ?? null;

// Agar payment ID nahi hai ya cart khali hai
if (!$tid || empty($_SESSION['cart'])) {
    die("Critical Error: Payment verification failed or Cart is empty.");
}

try {
    // 1. Stripe Se Payment Verify Karein
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    $intent = \Stripe\PaymentIntent::retrieve($tid);

    if ($intent->status !== 'succeeded') {
        die("Error: Payment status is " . $intent->status);
    }

    // 2. Transaction Start
    $pdo->beginTransaction();

    // 3. Cart Items Fetch aur Total Calculate
    $total = 0;
    $items = [];
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT product_name, price FROM products WHERE id = ?");
        $stmt->execute([$pid]);
        $product = $stmt->fetch();

        if ($product) {
            $item_total = $product['price'] * $qty;
            $total += $item_total;
            $items[] = [
                'id' => $pid, 
                'name' => $product['product_name'], 
                'qty' => $qty, 
                'price' => $product['price']
            ];
        }
    }

    // 4. Order Create Karein
    $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Paid')");
    $stmtOrder->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();

    // 5. Order Items Save Karein
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmtItem->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
    }

    // 6. INVOICE GENERATE (7 Columns = 7 Placeholders)
    $invoiceNum = "INV-" . date('Y') . "-" . str_pad($order_id, 4, "0", STR_PAD_LEFT);
    $tax = $total * 0.10; // 10% Tax logic
    $grandTotal = $total + $tax;

    // Yahan EXACTLY 7 '?' aur 7 values honi chahiye
    $sqlInv = "INSERT INTO invoices (invoice_number, order_id, customer_id, subtotal, tax_total, grand_total, status) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmtInv = $pdo->prepare($sqlInv);
    $stmtInv->execute([
        $invoiceNum,                // 1. invoice_number
        $order_id,                  // 2. order_id
        $_SESSION['user_id'],       // 3. customer_id
        (float)$total,              // 4. subtotal
        (float)$tax,                // 5. tax_total
        (float)$grandTotal,         // 6. grand_total
        'paid'                      // 7. status
    ]);
    
    $invoiceId = $pdo->lastInsertId();

    // 7. Invoice Items (Aapke Screenshot ke mutabik optional but recommended)
    $stmtInvItem = $pdo->prepare("INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmtInvItem->execute([
            $invoiceId, 
            $item['name'], 
            $item['qty'], 
            $item['price'], 
            ($item['qty'] * $item['price'])
        ]);
    }

    // 8. Commit Everything
    $pdo->commit();
    
    // 9. Cart Clear
    unset($_SESSION['cart']);

    // 10. Redirect to Premium Success Page
    header("Location: order-success.php?id=" . $invoiceId);
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Debugging ke liye error message print karein
    die("Database Error: " . $e->getMessage());
}
?>