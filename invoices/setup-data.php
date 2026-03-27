<?php
include 'functions.php';

echo "<h2>Starting Database Cleanup...</h2>";

// 1. Disable Foreign Keys to prevent errors during truncate
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// 2. Clear existing data
mysqli_query($conn, "TRUNCATE TABLE invoice_items");
mysqli_query($conn, "TRUNCATE TABLE invoices");

echo "<p style='color:green;'>✓ Tables cleared successfully.</p>";

// 3. Define Sample Data
$samples = [
    [
        'num' => 'INV-2026-001',
        'cust' => 1,
        'sub' => 1000,
        'status' => 'paid',
        'item' => 'Wireless Mouse'
    ],
    [
        'num' => 'INV-2026-002',
        'cust' => 2,
        'sub' => 4500,
        'status' => 'unpaid',
        'item' => 'Mechanical Keyboard'
    ],
    [
        'num' => 'INV-2026-003',
        'cust' => 3,
        'sub' => 12000,
        'status' => 'paid',
        'item' => 'Gaming Monitor'
    ]
];

// 4. Insert Loop
foreach ($samples as $s) {
    $tax = $s['sub'] * 0.10;
    $grand = $s['sub'] + $tax;
    $date = date('Y-m-d H:i:s');

    // Insert Invoice
    $sql_inv = "INSERT INTO invoices (invoice_number, customer_id, subtotal, tax_total, grand_total, status, created_at) 
                VALUES ('{$s['num']}', '{$s['cust']}', '{$s['sub']}', '$tax', '$grand', '{$s['status']}', '$date')";
    
    if (mysqli_query($conn, $sql_inv)) {
        $new_id = mysqli_insert_id($conn);
        
        // Insert Item
        $sql_item = "INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price) 
                     VALUES ('$new_id', '{$s['item']}', 1, '{$s['sub']}', '{$s['sub']}')";
        
        if (mysqli_query($conn, $sql_item)) {
            echo "<p style='color:blue;'>✓ Generated {$s['num']} for Customer {$s['cust']}</p>";
        } else {
            echo "<p style='color:red;'>× Item Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>× Invoice Error: " . mysqli_error($conn) . "</p>";
    }
}

// 5. Re-enable Foreign Keys
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

echo "<h3>Setup Complete! <a href='dashboard.php'>Go to Dashboard</a></h3>";
?>