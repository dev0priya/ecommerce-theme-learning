<?php
require '../include/load.php';
checkLogin();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // ecommerce_db check: Default currency ko delete nahi hone dena
        $stmt = $pdo->prepare("DELETE FROM currencies WHERE id = ? AND is_default = 0");
        $stmt->execute([$id]);
        
        // Wapas list page par bhej dena
        header("Location: currencies.php?status=deleted");
        exit();
    } catch (PDOException $e) {
        die("System Error: Could not delete currency.");
    }
} else {
    redirect('currencies.php');
}