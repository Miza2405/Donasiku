<?php
header('Content-Type: application/json');
session_start();
require_once '../koneksi.php';
require_once '../auth.php';

// Hanya admin yang boleh
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$program_id = intval($input['program_id'] ?? 0);

if (!$program_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Program ID tidak valid']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM programs WHERE id = ?");
$stmt->bind_param('i', $program_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Program berhasil dihapus']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus program']);
}
?>
