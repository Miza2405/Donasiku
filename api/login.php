<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../koneksi.php';

function sendJson($statusCode, $payload) {
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function findAccount($koneksi, $email, $role) {
    if (!in_array($role, ['user', 'admin'], true)) {
        return null;
    }

    $userQuery = "SELECT id, name, email, password, role, admin_role FROM users WHERE email = ? AND role = ?";
    $userStmt = $koneksi->prepare($userQuery);

    if (!$userStmt) {
        throw new Exception('Terjadi kesalahan server saat memeriksa akun');
    }

    $userStmt->bind_param('ss', $email, $role);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $user = $userResult->num_rows > 0 ? $userResult->fetch_assoc() : null;
    $userStmt->close();

    return $user;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email'], $input['password'], $input['role'])) {
        sendJson(400, [
            'success' => false,
            'message' => 'Email, password, dan role harus diisi'
        ]);
    }

    $email = trim($input['email']);
    $password = trim($input['password']);
    $role = trim($input['role']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJson(400, [
            'success' => false,
            'message' => 'Format email tidak valid'
        ]);
    }

    $account = findAccount($koneksi, $email, $role);

    if (!$account) {
        sendJson(401, [
            'success' => false,
            'message' => 'Email atau password salah'
        ]);
    }

    if (password_get_info($account['password'])['algo'] !== null) {
        $passwordValid = password_verify($password, $account['password']);
    } else {
        $passwordValid = $password === $account['password'];
    }

    if (!$passwordValid) {
        sendJson(401, [
            'success' => false,
            'message' => 'Email atau password salah'
        ]);
    }

    $_SESSION['user_id'] = $account['id'];
    $_SESSION['email'] = $account['email'];
    $_SESSION['role'] = $account['role'];
    $_SESSION['name'] = $account['name'];
    $_SESSION['admin_role'] = $account['admin_role'] ?? null;

    sendJson(200, [
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'id' => $account['id'],
            'name' => $account['name'],
            'email' => $account['email'],
            'role' => $_SESSION['role'],
            'admin_role' => $_SESSION['admin_role'] ?? null
        ]
    ]);
} catch (Exception $e) {
    sendJson(500, [
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $koneksi->close();
}
?>
