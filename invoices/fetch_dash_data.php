<?php
if (!isset($pdo)) {
    require_once '../include/load.php';
}

// CHANGED LIMIT TO 12
$limit = 12; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT i.*, c.name as customer_name 
        FROM invoices i 
        LEFT JOIN customers c ON i.customer_id = c.id 
        ORDER BY i.created_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$invoices = $stmt->fetchAll();

if($invoices) {
    foreach($invoices as $row) {
        $status = strtolower($row['status']);
        $statusClass = match($status) {
            'paid' => 'st-paid',
            'unpaid' => 'st-unpaid',
            'pending' => 'st-pending',
            default => 'st-cancelled',
        };

        echo "<tr class='hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all'>
                <td class='font-mono text-xs font-bold text-slate-400'>#{$row['invoice_number']}</td>
                <td class='font-bold text-slate-700 dark:text-slate-200'>".htmlspecialchars($row['customer_name'] ?? 'Guest')."</td>
                <td class='text-right amount-text'>₹".number_format($row['grand_total'], 2)."</td>
                <td class='text-center'>
                    <span class='status-badge {$statusClass}'>
                        ".strtoupper($row['status'])."
                    </span>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4' class='py-10 text-center text-slate-400 font-bold uppercase text-[10px]'>No entries found.</td></tr>";
}
?>