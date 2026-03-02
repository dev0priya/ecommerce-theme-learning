<?php
require '../../include/load.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Helper to update key-value settings
    function updateKey($key, $value, $pdo) {
        $sql = "INSERT INTO settings (setting_key, setting_value)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE setting_value = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key, $value, $value]);
    }

    // Save text settings
    updateKey('site_title', $_POST['site_title'], $pdo);
    updateKey('contact_email', $_POST['contact_email'], $pdo);
    updateKey('footer_text', $_POST['footer_text'], $pdo);

    // Save logo using centralized upload helper
    if (!empty($_FILES['site_logo']['name'])) {

        $logoName = uploadImage($_FILES['site_logo'], 'uploads');

        if ($logoName) {
            updateKey('site_logo', $logoName, $pdo);
        }
    }

    // Redirect back to settings page
    header('Location: ../../settings/index.php');
    exit;
}
