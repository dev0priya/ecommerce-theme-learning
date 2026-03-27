<?php
include 'functions.php';

if (isset($_POST['submit_invoice'])) {
    $cust_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $inv_num = generateInvoiceNumber($conn);
    
    // 1. Calculate Grand Totals from the arrays
    $subtotal = array_sum($_POST['item_total']);
    $tax = $subtotal * 0.10;
    $grand_total = $subtotal + $tax;

    // 2. Insert Main Invoice
    $query = "INSERT INTO invoices (invoice_number, customer_id, subtotal, tax_total, grand_total, status) 
              VALUES ('$inv_num', '$cust_id', '$subtotal', '$tax', '$grand_total', 'unpaid')";
    
    if (mysqli_query($conn, $query)) {
        $new_invoice_id = mysqli_insert_id($conn); // Get the ID of the invoice we just created

        // 3. Loop through items and insert them
        foreach ($_POST['item_name'] as $key => $name) {
            $qty = mysqli_real_escape_string($conn, $_POST['item_qty'][$key]);
            $price = mysqli_real_escape_string($conn, $_POST['item_price'][$key]);
            $total = $qty * $price;
            $name = mysqli_real_escape_string($conn, $name);

            mysqli_query($conn, "INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price) 
                                VALUES ('$new_invoice_id', '$name', '$qty', '$price', '$total')");
        }
        header("Location: index.php?success=1");
    }
}

$customers = mysqli_query($conn, "SELECT id, name FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Full Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">

<form method="POST" class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Invoice</h2>

    <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
        <label class="block font-bold text-indigo-900 mb-2">Customer</label>
        <select name="customer_id" class="w-full p-2 rounded border">
            <?php while($c = mysqli_fetch_assoc($customers)) echo "<option value='{$c['id']}'>{$c['name']}</option>"; ?>
        </select>
    </div>

    <table class="w-full mb-4" id="itemTable">
        <thead>
            <tr class="text-left text-gray-500 text-sm uppercase">
                <th class="pb-2">Product Name</th>
                <th class="pb-2">Qty</th>
                <th class="pb-2">Price</th>
                <th class="pb-2">Total</th>
            </tr>
        </thead>
        <tbody id="itemBody">
            <tr>
                <td><input type="text" name="item_name[]" class="w-full border p-2 rounded" placeholder="Product Name" required></td>
                <td><input type="number" name="item_qty[]" class="w-20 border p-2 rounded qty" value="1"></td>
                <td><input type="number" name="item_price[]" class="w-32 border p-2 rounded price" placeholder="0.00"></td>
                <td class="font-bold text-right p-2 item-total">$0.00</td>
                <input type="hidden" name="item_total[]" class="row-total-hidden">
            </tr>
        </tbody>
    </table>

    <button type="button" onclick="addRow()" class="text-indigo-600 font-bold mb-8">+ Add Another Item</button>

    <div class="border-t pt-6 text-right">
        <button type="submit" name="submit_invoice" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold shadow-lg">Save & Generate Invoice</button>
    </div>
</form>

<script>
// Simple logic to add new rows
function addRow() {
    const tr = `<tr>
        <td><input type="text" name="item_name[]" class="w-full border p-2 rounded" placeholder="Product Name" required></td>
        <td><input type="number" name="item_qty[]" class="w-20 border p-2 rounded qty" value="1"></td>
        <td><input type="number" name="item_price[]" class="w-32 border p-2 rounded price" placeholder="0.00"></td>
        <td class="font-bold text-right p-2 item-total">$0.00</td>
        <input type="hidden" name="item_total[]" class="row-total-hidden">
    </tr>`;
    document.getElementById('itemBody').insertAdjacentHTML('beforeend', tr);
}

// Simple calculation logic
document.addEventListener('input', function(e) {
    if(e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        const tr = e.target.closest('tr');
        const qty = tr.querySelector('.qty').value;
        const price = tr.querySelector('.price').value;
        const total = qty * price;
        tr.querySelector('.item-total').innerText = '$' + total.toFixed(2);
        tr.querySelector('.row-total-hidden').value = total;
    }
});
</script>
</body>
</html>