<?php
header('Content-Type: application/json');
session_start();
require_once '../koneksi.php';
require_once '../auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

function ensureDistributionsTable($koneksi) {
    $query = "CREATE TABLE IF NOT EXISTS fund_distributions (
        id INT(11) NOT NULL AUTO_INCREMENT,
        program_id INT(11) NOT NULL,
        beneficiary VARCHAR(150) NOT NULL,
        amount DECIMAL(15,2) NOT NULL,
        proof_image TEXT DEFAULT NULL,
        distributed_at DATE NOT NULL,
        created_by INT(11) DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY program_id (program_id),
        KEY created_by (created_by),
        CONSTRAINT fund_distributions_program_fk FOREIGN KEY (program_id) REFERENCES programs (id) ON DELETE CASCADE,
        CONSTRAINT fund_distributions_created_by_fk FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (!$koneksi->query($query)) {
        throw new Exception('Gagal menyiapkan tabel penyaluran: ' . $koneksi->error);
    }
}

$programId = (int)($_POST['program_id'] ?? 0);
$beneficiary = trim($_POST['beneficiary'] ?? '');
$amount = (float)($_POST['amount'] ?? 0);
$distributedAt = trim($_POST['distributed_at'] ?? '');
$createdBy = (int)($_SESSION['user_id'] ?? 0);

if (!$programId || !$beneficiary || $amount <= 0 || !$distributedAt) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Program, penerima manfaat, nominal, dan tanggal wajib diisi']);
    exit;
}

$date = DateTime::createFromFormat('Y-m-d', $distributedAt);
if (!$date || $date->format('Y-m-d') !== $distributedAt) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format tanggal penyaluran tidak valid']);
    exit;
}

$proofImage = null;
$filepath = null;

try {
    ensureDistributionsTable($koneksi);

    $programStmt = $koneksi->prepare("SELECT id FROM programs WHERE id = ?");
    if (!$programStmt) {
        throw new Exception('Gagal menyiapkan validasi program');
    }

    $programStmt->bind_param('i', $programId);
    $programStmt->execute();
    $programResult = $programStmt->get_result();

    if ($programResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Program tujuan tidak ditemukan']);
        exit;
    }

    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['proof_image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Upload bukti penyaluran gagal']);
            exit;
        }

        $uploadedFile = $_FILES['proof_image'];
        $maxSize = 5 * 1024 * 1024;
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if ($uploadedFile['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ukuran bukti terlalu besar (max 5MB)']);
            exit;
        }

        if (!in_array($uploadedFile['type'], $allowedMimeTypes, true)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipe file bukti tidak diizinkan']);
            exit;
        }

        $uploadDir = __DIR__ . '/../img';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new Exception('Gagal membuat folder upload');
        }

        $ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
        $filename = 'penyaluran_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $filepath = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $filepath)) {
            throw new Exception('Gagal menyimpan bukti penyaluran');
        }

        $proofImage = 'img/' . $filename;
    }

    $stmt = $koneksi->prepare("INSERT INTO fund_distributions (program_id, beneficiary, amount, proof_image, distributed_at, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception('Prepare error: ' . $koneksi->error);
    }

    $stmt->bind_param('isdssi', $programId, $beneficiary, $amount, $proofImage, $distributedAt, $createdBy);

    if (!$stmt->execute()) {
        if ($filepath) {
            @unlink($filepath);
        }
        throw new Exception('Gagal menyimpan data penyaluran');
    }

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Program penyaluran berhasil ditambahkan',
        'data' => [
            'id' => $stmt->insert_id,
            'proof_image' => $proofImage
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $koneksi->close();
}
?>
