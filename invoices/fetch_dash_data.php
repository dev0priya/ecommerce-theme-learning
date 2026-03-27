<?php
// functions.php ko load karein (isme database connection hona chahiye)
include_once 'functions.php'; 

// Agar functions.php mein connection nahi hai, toh apni db file include karein
// include_once 'db.php'; 

global $conn; // Connection variable ko global banayein

// Check karein ki connection kaam kar raha hai ya nahi
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$limit = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query run karein
$sql = "SELECT i.*, c.name FROM invoices i 
        LEFT JOIN customers c ON i.customer_id = c.id 
        ORDER BY i.created_at DESC 
        LIMIT $limit OFFSET $offset";

$query = mysqli_query($conn, $sql);

if($query && mysqli_num_rows($query) > 0) {
    while($row = mysqli_fetch_assoc($query)) {
        // Status class ke liye check
        $statusClass = (function_exists('getStatusClass')) ? getStatusClass($row['status']) : 'bg-gray-100';
        $formattedPrice = (function_exists('formatCurrency')) ? formatCurrency($row['grand_total']) : $row['grand_total'];

        echo "<tr class='hover:bg-gray-50 transition border-b'>
                <td class='px-8 py-4 font-mono font-bold text-indigo-600'>#{$row['invoice_number']}</td>
                <td class='px-8 py-4 text-gray-600 font-medium'>".($row['name'] ?? 'Guest')."</td>
                <td class='px-8 py-4 text-right font-bold text-gray-800'>{$formattedPrice}</td>
                <td class='px-8 py-4 text-center'>
                    <span class='px-3 py-1 rounded-full text-[10px] font-black uppercase {$statusClass}'>
                        {$row['status']}
                    </span>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4' class='p-10 text-center text-gray-400'>No entries found for this page.</td></tr>";
}
?>