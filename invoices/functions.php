<?php
// Database Credentials
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "ecommerce_db";

// Connect
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper: Format Money
function formatCurrency($amount) {
    return "$" . number_format((float)$amount, 2);
}

// Helper: Status Colors
function getStatusClass($status) {
    $status = strtolower($status);
    if ($status == 'paid') return 'bg-green-100 text-green-700';
    if ($status == 'unpaid') return 'bg-yellow-100 text-yellow-700';
    return 'bg-gray-100 text-gray-700';
}

function generateInvoiceNumber($conn) {
    $result = mysqli_query($conn, "SELECT id FROM invoices ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    $nextId = ($row) ? $row['id'] + 1 : 1;
    return "INV-" . date('Y') . "-" . str_pad($nextId, 3, "0", STR_PAD_LEFT);
}

?>