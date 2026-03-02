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

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $stmtItem->execute([
            $orderId,
            $p['id'],
            $qty,
            $p['price']
        ]);
    }

    // 6️⃣ COMMIT (SAVE EVERYTHING)
    $pdo->commit();

    // 7️⃣ Clear cart
    $_SESSION['cart'] = [];

    echo "<h1>Order placed successfully!</h1>";
    echo "<p>Order ID: <strong>#{$orderId}</strong></p>";
    echo "<p>Total Paid: ₹" . number_format($grandTotal, 2) . "</p>";
    echo "<a href='../index.php'>Continue Shopping</a>";

} catch (Exception $e) {
    // 8️⃣ ROLLBACK (UNDO ON ERROR)
    $pdo->rollBack();
    die("Order failed: " . $e->getMessage());
}
