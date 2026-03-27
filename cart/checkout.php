<?php
require '../include/load.php';

// User must be logged in
checkLogin();

// If cart is empty, go back to shop
if (empty($_SESSION['cart'])) {
    redirect('../index.php');
}

try {
    // 1️⃣ START TRANSACTION
    $pdo->beginTransaction();

    // 2️⃣ Get product IDs from cart
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Fetch product data again (SECURITY)
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    // 3️⃣ Calculate total again (never trust client)
    $grandTotal = 0;
    foreach ($products as $p) {
        $grandTotal += $p['price'] * $_SESSION['cart'][$p['id']];
    }

    // 4️⃣ Create order
    $stmt = $pdo->prepare(
        "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)"
    );
    $stmt->execute([$_SESSION['user_id'], $grandTotal]);

    $orderId = $pdo->lastInsertId();

    // 5️⃣ Insert order items
    $stmtItem = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    // --- NEW: Prepare Invoice Item Statement ---
    $stmtInvoiceItem = $pdo->prepare(
        "INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price)
         VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $itemPrice = $p['price'];
        $itemTotal = $qty * $itemPrice;

        $stmtItem->execute([$orderId, $p['id'], $qty, $itemPrice]);
    }

    // --- NEW: 6️⃣ Create Invoice (Project A Logic) ---
    $invoiceNum = "INV-" . date('Y') . "-" . str_pad($orderId, 4, "0", STR_PAD_LEFT);
    $tax = $grandTotal * 0.10;
    $finalTotal = $grandTotal + $tax;

    $stmtInv = $pdo->prepare(
        "INSERT INTO invoices (invoice_number, order_id, customer_id, subtotal, tax_total, grand_total, status) 
         VALUES (?, ?, ?, ?, ?, ?, 'unpaid')"
    );
    $stmtInv->execute([$invoiceNum, $orderId, $_SESSION['user_id'], $grandTotal, $tax, $finalTotal]);
    $invoiceId = $pdo->lastInsertId();

    // Insert Invoice Items
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $stmtInvoiceItem->execute([$invoiceId, $p['product_name'], $qty, $p['price'], ($qty * $p['price'])]);
    }

    // 7️⃣ COMMIT (SAVE EVERYTHING)
    $pdo->commit();

    // 8️⃣ Clear cart
    $_SESSION['cart'] = [];

    // --- REDIRECT TO YOUR NEW SUCCESS PAGE ---
    header("Location: order-success.php?id=" . $invoiceId);
    exit();

} catch (Exception $e) {
    // 9️⃣ ROLLBACK (UNDO ON ERROR)
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage());
}