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

$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$target_amount = floatval($_POST['target_amount'] ?? 0);
$description = trim($_POST['description'] ?? '');
$end_date = trim($_POST['end_date'] ?? null);

if (!$title || !$category || $target_amount <= 0 || !$description) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

// Validasi file upload
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Silakan upload gambar']);
    exit;
}

$uploadedFile = $_FILES['image'];
$maxSize = 5 * 1024 * 1024; // 5MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

// Validasi ukuran file
if ($uploadedFile['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)']);
    exit;
}

// Validasi MIME type
if (!in_array($uploadedFile['type'], $allowedMimeTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF']);
    exit;
}

// Buat folder img jika belum ada
$uploadDir = __DIR__ . '/../img';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal membuat folder upload']);
        exit;
    }
}

// Generate nama file unik dengan timestamp
$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
$filename = 'program_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
$filepath = $uploadDir . '/' . $filename;

// Pindahkan file ke folder
if (!move_uploaded_file($uploadedFile['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file']);
    exit;
}

// Simpan path relatif ke database
$image_url = 'img/' . $filename;

try {
    $stmt = $koneksi->prepare("INSERT INTO programs (title, category, description, target_amount, image_url, status, end_date) VALUES (?, ?, ?, ?, ?, 'active', ?)");
    
    if (!$stmt) {
        throw new Exception('Prepare error: ' . $koneksi->error);
    }
    
    $stmt->bind_param(
    'sssdss',
    $title,
    $category,
    $description,
    $target_amount,
    $image_url,
    $end_date
);
    
    if (!$stmt->execute()) {
        @unlink($filepath);
        throw new Exception('Gagal menyimpan data program');
    }
    
    $stmt->close();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Program berhasil ditambahkan',
        'data' => [
            'image_url' => $image_url,
            'filename' => $filename
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $koneksi->close();
}
?>