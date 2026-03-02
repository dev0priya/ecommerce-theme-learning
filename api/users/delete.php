<?php
require '../../include/load.php';

// STRICT: JSON only
header('Content-Type: application/json');

// Security: must be logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if (!$id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No ID provided'
    ]);
    exit;
}

// Prevent deleting yourself
if ($id == $_SESSION['user_id']) {
    echo json_encode([
        'status' => 'error',
        'message' => 'You cannot delete yourself!'
    ]);
    exit;
}

// Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

echo json_encode([
    'status' => 'success'
]);
