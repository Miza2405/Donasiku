<?php
header('Content-Type: application/json');
session_start();
require_once '../koneksi.php';
require_once '../auth.php';

// Cek apakah user sudah login
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Anda harus login untuk berdonasi'
    ]);
    exit;
}

// Hanya accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['program_id']) || !isset($input['amount']) || !isset($input['payment_method'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'program_id, amount, dan payment_method harus diisi'
    ]);
    exit;
}

$program_id = (int)$input['program_id'];
$amount = (float)$input['amount'];
$payment_method = trim($input['payment_method']);
$message = isset($input['message']) ? trim($input['message']) : '';
$user_id = $_SESSION['user_id'];

// Validasi amount
if ($amount <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Jumlah donasi harus lebih dari 0'
    ]);
    exit;
}

if ($amount > 999999999.99) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Jumlah donasi terlalu besar'
    ]);
    exit;
}

// Validasi program_id ada atau tidak
$programCheck = "SELECT id FROM programs WHERE id = ?";
$programStmt = $koneksi->prepare($programCheck);
$programStmt->bind_param('i', $program_id);
$programStmt->execute();
$programResult = $programStmt->get_result();

if ($programResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Program donasi tidak ditemukan'
    ]);
    exit;
}

$programStmt->close();

// Generate transaction code
$trx_code = 'TRX-' . date('YmdHis') . '-' . rand(1000, 9999);

try {
    // Start transaction
    $koneksi->begin_transaction();
    
    // Insert transaction dengan status 'pending' (nanti akan diverifikasi)
    $insertTrx = "INSERT INTO transactions (trx_code, user_id, program_id, amount, payment_method, message, status) 
                   VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $trxStmt = $koneksi->prepare($insertTrx);
    
    if (!$trxStmt) {
        throw new Exception("Prepare error: " . $koneksi->error);
    }
    
    $trxStmt->bind_param('siidss', $trx_code, $user_id, $program_id, $amount, $payment_method, $message);
    
    if (!$trxStmt->execute()) {
        throw new Exception("Execute error: " . $trxStmt->error);
    }
    
    $transaction_id = $trxStmt->insert_id;
    
    // Update collected_amount di program (untuk preview)
    // Note: Ini hanya update untuk preview, yang sesungguhnya di-update saat verified
    // Atau bisa juga di-update langsung kalau payment instant
    
    // Commit transaction
    $koneksi->commit();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Donasi berhasil dibuat, menunggu verifikasi pembayaran',
        'data' => [
            'id' => $transaction_id,
            'trx_code' => $trx_code,
            'amount' => $amount,
            'status' => 'pending',
            'payment_method' => $payment_method
        ]
    ]);
    
    $trxStmt->close();
    
} catch (Exception $e) {
    $koneksi->rollback();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal membuat donasi: ' . $e->getMessage()
    ]);
}

$koneksi->close();
?>
