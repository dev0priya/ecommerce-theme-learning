<?php
include 'functions.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Professional touch: Delete items first, then the invoice header
    // This prevents "orphaned" items in your database
    mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id = '$id'");
    $delete = mysqli_query($conn, "DELETE FROM invoices WHERE id = '$id'");

    if ($delete) {
        header("Location: index.php?deleted=1");
        exit();
    } else {
        die("Error deleting record: " . mysqli_error($conn));
    }
} else {
    header("Location: index.php");
    exit();
}
?>