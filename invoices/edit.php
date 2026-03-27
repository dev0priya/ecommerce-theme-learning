<?php
include 'functions.php';

// 1. Load Existing Data
if (!isset($_GET['id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch Header
$inv_res = mysqli_query($conn, "SELECT * FROM invoices WHERE id = '$id'");
$invoice = mysqli_fetch_assoc($inv_res);

if (!$invoice) {
    die("Invoice not found.");
}

// Fetch Items
$items_res = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id = '$id'");

// 2. Handle Update Logic
if (isset($_POST['update_invoice'])) {
    $cust_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // FIX: Check if item_total exists to prevent array_sum(null) error
    $item_totals = isset($_POST['item_total']) ? $_POST['item_total'] : [0];
    $subtotal = array_sum($item_totals);
    $tax = $subtotal * 0.10;
    $grand_total = $subtotal + $tax;

    // Update Main Invoice Header
    mysqli_query($conn, "UPDATE invoices SET 
        customer_id = '$cust_id', 
        subtotal = '$subtotal', 
        tax_total = '$tax', 
        grand_total = '$grand_total', 
        status = '$status' 
        WHERE id = '$id'");

    // Update Items: Clear old items and insert fresh ones
    mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id = '$id'");
    
    if (isset($_POST['item_name'])) {
        foreach ($_POST['item_name'] as $key => $name) {
            $qty = mysqli_real_escape_string($conn, $_POST['item_qty'][$key]);
            $price = mysqli_real_escape_string($conn, $_POST['item_price'][$key]);
            $total = (float)$qty * (float)$price;
            $name = mysqli_real_escape_string($conn, $name);

            mysqli_query($conn, "INSERT INTO invoice_items (invoice_id, product_name, quantity, price_per_unit, total_price) 
                                VALUES ('$id', '$name', '$qty', '$price', '$total')");
        }
    }
    header("Location: index.php?updated=1");
    exit();
}

$customers = mysqli_query($conn, "SELECT id, name FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<form method="POST" class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-8 border-t-4 border-amber-500">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Invoice</h2>
            <p class="text-amber-600 font-mono font-bold">#<?php echo $invoice['invoice_number']; ?></p>
        </div>
        <a href="index.php" class="text-gray-400 hover:text-gray-600 text-sm">← Back to List</a>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 p-4 rounded-lg">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Customer</label>
            <select name="customer_id" class="w-full p-2 border rounded bg-white outline-none focus:ring-2 focus:ring-amber-500">
                <?php while($c = mysqli_fetch_assoc($customers)): ?>
                    <option value="<?php echo $c['id']; ?>" <?php if($c['id'] == $invoice['customer_id']) echo 'selected'; ?>>
                        <?php echo $c['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Payment Status</label>
            <select name="status" class="w-full p-2 border rounded bg-white outline-none focus:ring-2 focus:ring-amber-500">
                <option value="unpaid" <?php if($invoice['status'] == 'unpaid') echo 'selected'; ?>>Unpaid</option>
                <option value="paid" <?php if($invoice['status'] == 'paid') echo 'selected'; ?>>Paid</option>
                <option value="cancelled" <?php if($invoice['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>
        </div>
    </div>

    <table class="w-full mb-4">
        <thead>
            <tr class="text-left text-gray-400 text-xs uppercase border-b">
                <th class="pb-2">Product Description</th>
                <th class="pb-2 w-24">Qty</th>
                <th class="pb-2 w-32">Unit Price</th>
                <th class="pb-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody id="itemBody">
            <?php while($item = mysqli_fetch_assoc($items_res)): ?>
            <tr class="border-b last:border-0 item-row">
                <td class="py-3 pr-4">
                    <input type="text" name="item_name[]" value="<?php echo $item['product_name']; ?>" class="w-full border p-2 rounded focus:border-amber-500 outline-none" required>
                </td>
                <td class="py-3 pr-4">
                    <input type="number" name="item_qty[]" value="<?php echo $item['quantity']; ?>" class="w-full border p-2 rounded qty" min="1">
                </td>
                <td class="py-3 pr-4">
                    <input type="number" name="item_price[]" value="<?php echo $item['price_per_unit']; ?>" class="w-full border p-2 rounded price" step="0.01">
                </td>
                <td class="py-3 text-right font-bold text-gray-700 item-total-display">
                    <?php echo formatCurrency($item['total_price']); ?>
                </td>
                <input type="hidden" name="item_total[]" value="<?php echo $item['total_price']; ?>" class="row-total-hidden">
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="flex justify-between items-end mt-8 pt-6 border-t">
        <div class="text-gray-400 text-sm italic">
            * Changes are only permanent once you click Update.
        </div>
        <div class="text-right">
            <button type="submit" name="update_invoice" class="bg-amber-500 hover:bg-amber-600 text-white px-10 py-3 rounded-lg font-bold shadow-lg transition transform active:scale-95">
                Update Invoice Detail
            </button>
        </div>
    </div>
</form>

<script>
// Live Calculation Logic
document.addEventListener('input', function(e) {
    if(e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        const tr = e.target.closest('tr');
        const qty = parseFloat(tr.querySelector('.qty').value) || 0;
        const price = parseFloat(tr.querySelector('.price').value) || 0;
        
        const total = qty * price;
        
        // Update visual display
        tr.querySelector('.item-total-display').innerText = '$' + total.toFixed(2);
        
        // Update the hidden input that PHP uses
        tr.querySelector('.row-total-hidden').value = total;
    }
});
</script>

</body>
</html>