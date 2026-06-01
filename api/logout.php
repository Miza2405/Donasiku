<?php
header('Content-Type: application/json');
session_start();

// Destroy session
session_unset();
session_destroy();

// Response sukses
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Logout berhasil'
]);
?>
