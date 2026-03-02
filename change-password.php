<?php
require 'include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {

$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

$stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
$stmt->execute([$userId]);
$hash = $stmt->fetchColumn();

if (!password_verify($current,$hash)) {
    $error = "Current password incorrect.";
}
elseif ($new !== $confirm) {
    $error = "Passwords do not match.";
}
else {
    $newHash = password_hash($new,PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password=? WHERE id=?")
        ->execute([$newHash,$userId]);
    $success="Password updated successfully.";
}
}
?>