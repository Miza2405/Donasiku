<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../koneksi.php';

if (!isLoggedIn() || !isUser()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Anda harus login sebagai user'
    ]);
    exit;
}

$userId = (int) $_SESSION['user_id'];

function getProfile($koneksi, $userId) {
    $query = "SELECT id, name, email, phone, avatar_url FROM users WHERE id = ?";
    $stmt = $koneksi->prepare($query);

    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query profil');
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Profil user tidak ditemukan');
    }

    $profile = $result->fetch_assoc();
    $stmt->close();

    return $profile;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $profile = getProfile($koneksi, $userId);

        $_SESSION['name'] = $profile['name'];
        $_SESSION['email'] = $profile['email'];

        echo json_encode([
            'success' => true,
            'data' => $profile
        ]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true);
        } else {
            $input = $_POST;
        }

        $name = isset($input['name']) ? trim($input['name']) : '';
        $phone = isset($input['phone']) ? trim($input['phone']) : '';
        $avatarUrl = null;

        if ($name === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Nama lengkap harus diisi'
            ]);
            exit;
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $_FILES['avatar'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jfif'];
            if (!in_array($uploaded['type'], $allowedTypes, true)) {
                throw new Exception('Format gambar tidak didukung');
            }

            if ($uploaded['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran gambar tidak boleh lebih dari 5MB');
            }

            $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
            $prefix = preg_replace('/[^a-z0-9]/', '', strtolower(explode('@', $email)[0] ?? 'user'));
            if ($prefix === '') {
                $prefix = 'user' . $userId;
            }

            $uploadDate = date('dmY');
            $uploadDir = __DIR__ . '/../profile-user';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $existingFiles = glob($uploadDir . '/' . $prefix . '-*-' . $uploadDate . '.*');
            $nextIndex = count($existingFiles) + 1;
            $extension = strtolower(pathinfo($uploaded['name'], PATHINFO_EXTENSION));
            if ($extension === '') {
                $extension = 'jpg';
            }

            $filename = $prefix . '-' . $nextIndex . '-' . $uploadDate . '.' . $extension;
            $targetPath = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($uploaded['tmp_name'], $targetPath)) {
                throw new Exception('Gagal menyimpan gambar profil');
            }

            $avatarUrl = 'profile-user/' . $filename;
        }

        $query = "UPDATE users SET name = ?, phone = ?, avatar_url = COALESCE(?, avatar_url) WHERE id = ?";
        $stmt = $koneksi->prepare($query);

        if (!$stmt) {
            throw new Exception('Gagal menyiapkan query update profil');
        }

        $stmt->bind_param('sssi', $name, $phone, $avatarUrl, $userId);
        $stmt->execute();
        $stmt->close();

        $profile = getProfile($koneksi, $userId);

        $_SESSION['name'] = $profile['name'];
        $_SESSION['email'] = $profile['email'];

        echo json_encode([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $profile
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $koneksi->close();
}
?>
