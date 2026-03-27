<?php
include 'functions.php';

$query = "SELECT i.*, c.name as customer_name 
          FROM invoices i 
          LEFT JOIN customers c ON i.customer_id = c.id 
          ORDER BY i.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-6xl mx-auto">
    <?php if(isset($_GET['success'])): ?>
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">✓ Invoice created successfully!</div>
    <?php endif; ?>
    
    <?php if(isset($_GET['updated'])): ?>
        <div class="bg-blue-500 text-white p-4 rounded-lg mb-6 shadow">i Invoice updated successfully!</div>
    <?php endif; ?>

    <?php if(isset($_GET['deleted'])): ?>
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow">✕ Invoice deleted permanently.</div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Invoices</h1>
        <a href="add.php" class="bg-indigo-600 text-white py-2 px-6 rounded-lg font-bold hover:bg-indigo-700 transition">Create New</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Number</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Customer</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Total</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-indigo-600"><?php echo $row['invoice_number']; ?></td>
                    <td class="px-6 py-4 text-gray-700"><?php echo $row['customer_name'] ?? 'Guest'; ?></td>
                    <td class="px-6 py-4 font-bold text-gray-900"><?php echo formatCurrency($row['grand_total']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo getStatusClass($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-4">
                        <a href="view.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-amber-500 hover:text-amber-700 font-semibold">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this invoice? This cannot be undone.')" 
                           class="text-red-400 hover:text-red-600 font-semibold">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>