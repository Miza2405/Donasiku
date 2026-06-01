<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../auth.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

$dir = __DIR__ . '/../qris';
$result = [];
if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp','jfif'])) {
            $result[] = 'qris/' . $f;
        }
    }
}

echo json_encode(['success' => true, 'data' => $result]);

?>
