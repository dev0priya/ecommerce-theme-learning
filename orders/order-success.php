<?php
// Path ko apne folder structure ke hisaab se check kar lein
include '../admin/invoices/functions.php'; 

$inv_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if (!$inv_id) {
    header("Location: ../index.php"); 
    exit();
}

// Invoice details fetch karna
$query = "SELECT i.*, c.name, c.email FROM invoices i 
          JOIN customers c ON i.customer_id = c.id 
          WHERE i.id = '$inv_id'";
$result = mysqli_query($conn, $query);
$invoice = mysqli_fetch_assoc($result);

if (!$invoice) {
    die("Order details not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

<div class="max-w-md w-full">
    <div id="receipt-card" class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200">
        <div class="bg-indigo-600 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-black">Order Successful!</h1>
            <p class="text-indigo-100 text-sm opacity-90">Thank you for shopping with us.</p>
        </div>

        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Invoice No.</span>
                <span class="font-mono font-bold text-gray-800">#<?php echo $invoice['invoice_number']; ?></span>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between border-b border-dashed pb-2">
                    <span class="text-gray-500 text-sm">Customer</span>
                    <span class="text-gray-800 font-semibold text-sm"><?php echo $invoice['name']; ?></span>
                </div>
                <div class="flex justify-between border-b border-dashed pb-2">
                    <span class="text-gray-500 text-sm">Date</span>
                    <span class="text-gray-800 font-semibold text-sm"><?php echo date('d M, Y', strtotime($invoice['created_at'])); ?></span>
                </div>
                <div class="flex justify-between pt-2">
                    <span class="text-gray-500 font-bold uppercase text-xs">Total Amount Paid</span>
                    <span class="text-xl font-black text-indigo-600"><?php echo formatCurrency($invoice['grand_total']); ?></span>
                </div>
            </div>

            <p class="text-[10px] text-center text-gray-400 italic mb-4">
                This is a computer-generated receipt for Project A.
            </p>
        </div>
    </div>

    <div class="mt-6 space-y-3 no-download">
        <button onclick="downloadReceipt()" class="w-full bg-white border-2 border-indigo-600 text-indigo-600 py-3 rounded-xl font-bold hover:bg-indigo-50 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Save as Image
        </button>
        
        <a href="../index.php" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
            Continue Shopping
        </a>
    </div>
</div>

<script>
function downloadReceipt() {
    const element = document.getElementById('receipt-card');
    html2canvas(element, {
        scale: 2, // Quality badhane ke liye
        backgroundColor: "#F3F4F6"
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Receipt-<?php echo $invoice['invoice_number']; ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>

</body>
</html>