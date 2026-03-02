<?php
require '../../include/load.php';

header('Content-Type: application/json');

// Placeholder response
echo json_encode([
    'status' => 'success',
    'message' => 'Product upload endpoint ready'
]);
