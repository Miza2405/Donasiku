<?php
header('Content-Type: application/json');
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

function ensureServiceMessagesTable($koneksi) {
    $query = "CREATE TABLE IF NOT EXISTS service_messages (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(120) NOT NULL,
        category VARCHAR(80) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new','read','archived') NOT NULL DEFAULT 'new',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY status (status),
        KEY created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (!$koneksi->query($query)) {
        throw new Exception('Gagal menyiapkan tabel layanan: ' . $koneksi->error);
    }
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$category = trim($_POST['category'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$category || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama, email, kategori, dan pesan wajib diisi']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit;
}

try {
    ensureServiceMessagesTable($koneksi);

    $stmt = $koneksi->prepare("INSERT INTO service_messages (name, email, category, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception('Prepare error: ' . $koneksi->error);
    }

    $stmt->bind_param('ssss', $name, $email, $category, $message);
    if (!$stmt->execute()) {
        throw new Exception('Gagal menyimpan pesan layanan');
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Pesan berhasil dikirim. Tim layanan kami akan segera menindaklanjuti.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $koneksi->close();
}
?>
