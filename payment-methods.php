<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../koneksi.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Harus login']);
    exit;
}

$dataFile = __DIR__ . '/../data/payment-methods.json';
if (!is_dir(dirname($dataFile))) {
    mkdir(dirname($dataFile), 0755, true);
}

function loadMethods($path) {
    if (!file_exists($path)) return [];
    $raw = file_get_contents($path);
    $arr = json_decode($raw, true);
    return is_array($arr) ? $arr : [];
}

function saveMethods($path, $arr) {
    file_put_contents($path, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

try {
    $methods = loadMethods($dataFile);

    // Semua user yang login boleh melihat metode pembayaran
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $visibleMethods = array_values(array_filter($methods, function($m) {
            return !isset($m['active']) || $m['active'] === true;
        }));

        echo json_encode(['success' => true, 'data' => $visibleMethods]);
        exit;
    }

    // Selain GET harus admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses admin diperlukan']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? 'create';

        if ($action === 'create' || $action === 'update') {
            $type = trim($_POST['type'] ?? 'bank');
            $name = trim($_POST['name'] ?? '');
            $account = trim($_POST['account'] ?? '');
            $owner = trim($_POST['owner'] ?? '');
            $active = isset($_POST['active']) ? ($_POST['active'] === '1' ? true : false) : true;
            $id = isset($_POST['id']) ? trim($_POST['id']) : null;

            if ($name === '') {
                throw new Exception('Nama metode harus diisi');
            }

            $imageUrl = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploaded = $_FILES['image'];
                $allowed = ['image/jpeg','image/png','image/gif','image/webp','image/jfif'];

                if (!in_array($uploaded['type'], $allowed, true)) {
                    throw new Exception('Format gambar tidak didukung');
                }

                if ($uploaded['size'] > 5 * 1024 * 1024) {
                    throw new Exception('Ukuran gambar terlalu besar');
                }

                $uploadDir = __DIR__ . '/../payments';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $ext = pathinfo($uploaded['name'], PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'method-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target = $uploadDir . '/' . $filename;

                if (!move_uploaded_file($uploaded['tmp_name'], $target)) {
                    throw new Exception('Gagal menyimpan gambar');
                }

                $imageUrl = 'payments/' . $filename;
            }

            if (empty($imageUrl) && isset($_POST['image_url']) && trim($_POST['image_url']) !== '') {
                $imageUrl = trim($_POST['image_url']);
            }

            if ($action === 'create') {
                $methods[] = [
                    'id' => uniqid('m'),
                    'type' => $type,
                    'name' => $name,
                    'account' => $account,
                    'owner' => $owner,
                    'image' => $imageUrl,
                    'active' => $active
                ];
            } else {
                $found = false;

                foreach ($methods as &$m) {
                    if ($m['id'] === $id) {
                        $m['type'] = $type;
                        $m['name'] = $name;
                        $m['account'] = $account;
                        $m['owner'] = $owner;

                        if ($imageUrl) {
                            $m['image'] = $imageUrl;
                        }

                        $m['active'] = $active;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    throw new Exception('Metode tidak ditemukan');
                }
            }

            saveMethods($dataFile, $methods);
            echo json_encode(['success' => true, 'data' => $methods]);
            exit;
        }

        if ($action === 'delete') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                throw new Exception('ID diperlukan');
            }

            $methods = array_values(array_filter($methods, function($m) use ($id) {
                return $m['id'] !== $id;
            }));

            saveMethods($dataFile, $methods);
            echo json_encode(['success' => true, 'data' => $methods]);
            exit;
        }
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
