<?php
include 'functions.php';

// 1. Stats Queries for the Cards
$rev_query = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'paid'");
$revenue = mysqli_fetch_assoc($rev_query)['total'] ?? 0;

$pend_query = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'unpaid'");
$pending = mysqli_fetch_assoc($pend_query)['total'] ?? 0;

$count_query = mysqli_query($conn, "SELECT COUNT(id) as count FROM invoices");
$inv_count = mysqli_fetch_assoc($count_query)['count'] ?? 0;

// 2. Count Total Pages for the AJAX buttons
$limit = 7;
$total_res = mysqli_query($conn, "SELECT COUNT(id) as total FROM invoices");
$total_rows = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loading-fade { opacity: 0.4; pointer-events: none; transition: opacity 0.2s; }
        .active-page { background-color: #4f46e5 !important; color: white !important; }
    </style>
</head>
<body class="bg-gray-100 p-8 font-sans">

<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800">Business Dashboard</h1>
            <p class="text-gray-400 text-sm font-medium">Monitoring Project A Performance</p>
        </div>
        
        <div class="w-full md:w-96">
            <form action="index.php" method="GET" class="relative">
                <input type="text" name="search" placeholder="Search Invoice or Customer..." 
                       class="w-full pl-10 pr-4 py-2 rounded-xl border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm transition">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        <div class="flex gap-2">
            <a href="add.php" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">New Invoice</a>
            <a href="index.php" class="bg-white border text-gray-600 px-6 py-2 rounded-xl font-bold hover:bg-gray-50 transition">Manage All</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-green-500">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Revenue</p>
            <p class="text-3xl font-black text-gray-800 mt-1"><?php echo formatCurrency($revenue); ?></p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-amber-500">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pending Balance</p>
            <p class="text-3xl font-black text-gray-800 mt-1"><?php echo formatCurrency($pending); ?></p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-indigo-500">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Invoices</p>
            <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $inv_count; ?></p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b bg-gray-50/50 flex justify-between items-center">
            <h2 class="font-bold text-gray-700">Recent Activity</h2>
            
            <div class="flex gap-1" id="pagination-controls">
                <?php for($i = 1; $i <= min($total_pages, 5); $i++): ?>
                    <button onclick="changePage(<?php echo $i; ?>, this)" 
                            class="page-btn p-1 px-3 rounded-lg text-xs font-bold transition <?php echo ($i==1) ? 'active-page' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'; ?>">
                        <?php echo $i; ?>
                    </button>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b">
                        <th class="px-8 py-4">Invoice Number</th>
                        <th class="px-8 py-4">Customer Name</th>
                        <th class="px-8 py-4 text-right">Grand Total</th>
                        <th class="px-8 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="invoice-rows" class="divide-y divide-gray-50">
                    <?php include 'fetch_dash_data.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function changePage(page, btnElement) {
    const tableBody = document.getElementById('invoice-rows');
    const allButtons = document.querySelectorAll('.page-btn');

    // Visual feedback: Add loading state
    tableBody.classList.add('loading-fade');

    // Fetch data using AJAX
    fetch('fetch_dash_data.php?page=' + page)
        .then(response => response.text())
        .then(html => {
            // Update table content
            tableBody.innerHTML = html;
            tableBody.classList.remove('loading-fade');

            // Update active button styling
            allButtons.forEach(btn => btn.classList.remove('active-page', 'bg-gray-100', 'text-gray-500'));
            allButtons.forEach(btn => btn.classList.add('bg-gray-100', 'text-gray-500'));
            btnElement.classList.add('active-page');
            btnElement.classList.remove('bg-gray-100', 'text-gray-500');
        })
        .catch(err => {
            console.error('Error loading data:', err);
            tableBody.classList.remove('loading-fade');
        });
}
</script>

</body>
</html>