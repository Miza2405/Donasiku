<?php
header('Content-Type: application/json');
session_start();

// Import koneksi database
require_once '../koneksi.php';

// Tangkap request POST
$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Nama, email, dan password harus diisi'
    ]);
    exit;
}

$name = trim($input['name']);
$email = trim($input['email']);
$password = trim($input['password']);

// Validasi length
if (strlen($name) < 3) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Nama minimal 3 karakter'
    ]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Password minimal 6 karakter'
    ]);
    exit;
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Format email tidak valid'
    ]);
    exit;
}

// Cek apakah email sudah terdaftar
$checkQuery = "SELECT id FROM users WHERE email = ?";
$checkStmt = $koneksi->prepare($checkQuery);

if (!$checkStmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server'
    ]);
    exit;
}

$checkStmt->bind_param('s', $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'message' => 'Email sudah terdaftar'
    ]);
    exit;
}

$checkStmt->close();

// Hash password menggunakan password_hash
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user baru
$insertQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
$insertStmt = $koneksi->prepare($insertQuery);

if (!$insertStmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server'
    ]);
    exit;
}

$insertStmt->bind_param('sss', $name, $email, $hashedPassword);

if (!$insertStmt->execute()) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal membuat akun'
    ]);
    exit;
}

$userId = $insertStmt->insert_id;

// Set session setelah register sukses
$_SESSION['user_id'] = $userId;
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;
$_SESSION['role'] = 'user';

// Response sukses
http_response_code(201);
echo json_encode([
    'success' => true,
    'message' => 'Akun berhasil dibuat',
    'data' => [
        'id' => $userId,
        'name' => $name,
        'email' => $email,
        'role' => 'user'
    ]
]);

$insertStmt->close();
$koneksi->close();
?>
