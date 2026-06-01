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

$program_id = intval($_POST['program_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$target_amount = floatval($_POST['target_amount'] ?? 0);
$description = trim($_POST['description'] ?? '');
$image_url = trim($_POST['image_url'] ?? '');
$status = trim($_POST['status'] ?? 'active');

if (!$program_id || !$title || !$category || $target_amount <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

if (!in_array($status, ['active', 'completed', 'cancelled'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
    exit;
}

$oldImageUrl = $image_url;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Gagal mengunggah gambar']);
        exit;
    }

    $uploadedFile = $_FILES['image'];
    $maxSize = 5 * 1024 * 1024;
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if ($uploadedFile['size'] > $maxSize) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)']);
        exit;
    }

    if (!in_array($uploadedFile['type'], $allowedMimeTypes, true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF']);
        exit;
    }

    $uploadDir = __DIR__ . '/../img';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal membuat folder upload']);
            exit;
        }
    }

    $ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $filename = 'program_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $filepath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $filepath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file']);
        exit;
    }

    if ($oldImageUrl && strpos($oldImageUrl, 'img/') === 0) {
        @unlink(__DIR__ . '/../' . $oldImageUrl);
    }

    $image_url = 'img/' . $filename;
}

if ($image_url === '') {
    $query = "SELECT image_url FROM programs WHERE id = ?";
    $queryStmt = $koneksi->prepare($query);
    $queryStmt->bind_param('i', $program_id);
    $queryStmt->execute();
    $queryResult = $queryStmt->get_result();
    $existingProgram = $queryResult->fetch_assoc();
    $image_url = $existingProgram['image_url'] ?? null;
    $queryStmt->close();
}

$stmt = $koneksi->prepare("UPDATE programs SET title = ?, category = ?, description = ?, target_amount = ?, image_url = ?, status = ? WHERE id = ?");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $koneksi->error]);
    exit;
}

$stmt->bind_param('sssdssi', $title, $category, $description, $target_amount, $image_url, $status, $program_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Program berhasil diperbarui']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui program: ' . $stmt->error]);
}
$stmt->close();
?>
