<?php
include 'functions.php';

// Security check: Ensure ID exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch Invoice & Customer Data
$inv_res = mysqli_query($conn, "SELECT i.*, c.name, c.address, c.email FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.id = '$id'");
$invoice = mysqli_fetch_assoc($inv_res);

if (!$invoice) {
    die("Invoice not found.");
}

// Fetch Line Items
$items_res = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id = '$id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preview - <?php echo $invoice['invoice_number']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } body { background: white; padding: 0; } .print-card { box-shadow: none; border: none; } }
    </style>
</head>
<body class="bg-gray-100 py-10 px-4">

<div class="max-w-4xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden print-card">
    <div class="no-print bg-gray-800 p-4 flex justify-between items-center text-white">
        <span>Previewing Invoice</span>
        <div class="space-x-2">
            <button onclick="window.print()" class="bg-blue-600 px-4 py-1 rounded hover:bg-blue-700">Print / Save PDF</button>
            <a href="index.php" class="bg-gray-600 px-4 py-1 rounded hover:bg-gray-500">Back to List</a>
        </div>
    </div>

    <div class="p-10">
        <div class="flex justify-between border-b pb-8 mb-8">
            <div>
                <h2 class="text-3xl font-black text-indigo-600">PROJECT A</h2>
                <p class="text-gray-500 text-sm">Official Purchase Receipt</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold uppercase tracking-widest text-gray-400">Invoice</h3>
                <p class="text-lg font-bold">#<?php echo $invoice['invoice_number']; ?></p>
                <p class="text-gray-500 text-sm"><?php echo date('F d, Y', strtotime($invoice['created_at'])); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-10">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Billed To</p>
                <p class="font-bold text-gray-800 text-lg"><?php echo $invoice['name'] ?? 'Walk-in Customer'; ?></p>
                <p class="text-gray-600"><?php echo $invoice['address'] ?? 'N/A'; ?></p>
                <p class="text-gray-600"><?php echo $invoice['email'] ?? ''; ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Status</p>
                <span class="text-xl font-black uppercase text-indigo-500"><?php echo $invoice['status']; ?></span>
            </div>
        </div>

        <table class="w-full text-left mb-10">
            <thead>
                <tr class="border-b-2 border-gray-800">
                    <th class="py-3 font-bold text-gray-700">Item Description</th>
                    <th class="py-3 text-center font-bold text-gray-700">Qty</th>
                    <th class="py-3 text-right font-bold text-gray-700">Unit Price</th>
                    <th class="py-3 text-right font-bold text-gray-700">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while($item = mysqli_fetch_assoc($items_res)): ?>
                <tr>
                    <td class="py-4 text-gray-800 font-medium"><?php echo $item['product_name']; ?></td>
                    <td class="py-4 text-center text-gray-600"><?php echo $item['quantity']; ?></td>
                    <td class="py-4 text-right text-gray-600"><?php echo formatCurrency($item['price_per_unit']); ?></td>
                    <td class="py-4 text-right font-bold text-gray-900"><?php echo formatCurrency($item['total_price']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="w-full max-w-xs space-y-2 text-right">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span><?php echo formatCurrency($invoice['subtotal']); ?></span>
                </div>
                <div class="flex justify-between text-gray-600 border-b pb-2">
                    <span>Tax Total</span>
                    <span><?php echo formatCurrency($invoice['tax_total']); ?></span>
                </div>
                <div class="flex justify-between text-2xl font-black text-indigo-600 pt-2">
                    <span>Grand Total</span>
                    <span><?php echo formatCurrency($invoice['grand_total']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>