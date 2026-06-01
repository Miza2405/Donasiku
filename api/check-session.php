<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth.php';

// Tangkap request POST untuk check session
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = checkSessionStatus();
    
    http_response_code(200);
    echo json_encode($status);
    exit;
}

http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Method tidak diizinkan'
]);
?>
