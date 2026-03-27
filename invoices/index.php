<?php
include_once 'functions.php';

// --- PAGINATION LOGIC ---
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- SEARCH & FILTER LOGIC ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = " WHERE 1=1 "; // Default condition

if ($search != '') {
    // Agar search 'unpaid' ya 'paid' hai, toh status column check karo
    if (strtolower($search) == 'unpaid' || strtolower($search) == 'paid') {
        $where .= " AND i.status = '$search' ";
    } else {
        // Baaki cases mein name ya invoice number check karo
        $where .= " AND (i.invoice_number LIKE '%$search%' OR c.name LIKE '%$search%') ";
    }
}

// Count Total for Pagination
$count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id $where");
$total_rows = mysqli_fetch_assoc($count_res)['total'];
$total_pages = ceil($total_rows / $limit);

// Main Query
$query = "SELECT i.*, c.name as customer_name FROM invoices i 
          LEFT JOIN customers c ON i.customer_id = c.id 
          $where 
          ORDER BY i.created_at DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Invoices | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">All Invoices (<?php echo $total_rows; ?>)</h1>
        <a href="dashboard.php" class="text-indigo-600 font-bold">← Back to Dashboard</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Invoice #</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-indigo-600"><?php echo $row['invoice_number']; ?></td>
                    <td class="px-6 py-4 font-medium"><?php echo $row['customer_name'] ?? 'Guest'; ?></td>
                    <td class="px-6 py-4 text-gray-500 text-sm"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                    <td class="px-6 py-4 font-bold"><?php echo formatCurrency($row['grand_total']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?php echo getStatusClass($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="view.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">View</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="text-red-400">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center items-center gap-2">
        <?php if($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>" class="px-4 py-2 bg-white border rounded-lg shadow-sm hover:bg-gray-50">Previous</a>
        <?php endif; ?>

        <div class="px-4 py-2 font-bold text-gray-600">
            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
        </div>

        <?php if($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>" class="px-4 py-2 bg-white border rounded-lg shadow-sm hover:bg-gray-50">Next</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>