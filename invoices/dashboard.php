<?php
include 'functions.php';

// 1. Get Total Revenue (Paid Invoices)
$rev_query = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'paid'");
$revenue = mysqli_fetch_assoc($rev_query)['total'] ?? 0;

// 2. Get Pending Amount (Unpaid Invoices)
$pend_query = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'unpaid'");
$pending = mysqli_fetch_assoc($pend_query)['total'] ?? 0;

// 3. Get Total Invoice Count
$count_query = mysqli_query($conn, "SELECT COUNT(id) as count FROM invoices");
$inv_count = mysqli_fetch_assoc($count_query)['count'] ?? 0;

// 4. Get 5 Most Recent Invoices
$recent_query = mysqli_query($conn, "SELECT i.*, c.name FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id ORDER BY i.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Business Overview</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-sm font-bold text-gray-400 uppercase">Total Revenue</p>
            <p class="text-3xl font-black text-gray-800"><?php echo formatCurrency($revenue); ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
            <p class="text-sm font-bold text-gray-400 uppercase">Outstanding Balance</p>
            <p class="text-3xl font-black text-gray-800"><?php echo formatCurrency($pending); ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
            <p class="text-sm font-bold text-gray-400 uppercase">Total Invoices</p>
            <p class="text-3xl font-black text-gray-800"><?php echo $inv_count; ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="font-bold text-gray-800 text-lg">Recent Invoices</h2>
            <a href="index.php" class="text-indigo-600 text-sm font-bold hover:underline">View All →</a>
        </div>
        <table class="w-full text-left">
            <tbody class="divide-y">
                <?php while($row = mysqli_fetch_assoc($recent_query)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-700"><?php echo $row['invoice_number']; ?></td>
                    <td class="px-6 py-4 text-gray-500"><?php echo $row['name'] ?? 'Guest'; ?></td>
                    <td class="px-6 py-4 font-bold text-right"><?php echo formatCurrency($row['grand_total']); ?></td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase <?php echo getStatusClass($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>