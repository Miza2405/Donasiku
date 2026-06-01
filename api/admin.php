<?php
header('Content-Type: application/json');
session_start();
require_once '../koneksi.php';
require_once '../auth.php';

// Cek apakah user sudah login sebagai admin
requireAdmin();

$action = isset($_GET['action']) ? $_GET['action'] : 'transactions';

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
        throw new Exception("Gagal menyiapkan tabel penyaluran: " . $koneksi->error);
    }
}

try {
    // ===== GET ALL TRANSACTIONS FOR ADMIN =====
    if ($action === 'transactions') {
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        $query = "SELECT 
                    t.id,
                    t.trx_code,
                    t.amount,
                    t.payment_method,
                    t.message,
                    t.status,
                    t.created_at,
                    u.name as donor_name,
                    u.email as donor_email,
                    p.title as program_title
                  FROM transactions t
                  JOIN users u ON t.user_id = u.id
                  JOIN programs p ON t.program_id = p.id";
        
        if ($status && in_array($status, ['pending', 'success', 'failed'])) {
            $query .= " WHERE t.status = '$status'";
        }
        
        $query .= " ORDER BY t.created_at DESC";
        
        $result = $koneksi->query($query);
        
        if (!$result) {
            throw new Exception("Query error: " . $koneksi->error);
        }
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $transactions,
            'count' => count($transactions)
        ]);
    }
    
    // ===== GET PROGRAM STATISTICS =====
    else if ($action === 'stats') {
        ensureDistributionsTable($koneksi);

        // Get program stats
        $programQuery = "SELECT 
                          COUNT(*) as total_programs,
                          SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_programs,
                          SUM(collected_amount) as total_collected,
                          SUM(target_amount) as total_target
                        FROM programs";
        
        $programResult = $koneksi->query($programQuery);
        $programStats = $programResult->fetch_assoc();
        
        // Get transaction stats
        $transactionQuery = "SELECT 
                              COUNT(*) as total_transactions,
                              SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_success,
                              SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                              SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count
                            FROM transactions";
        
        $transactionResult = $koneksi->query($transactionQuery);
        $transactionStats = $transactionResult->fetch_assoc();
        
        // Get donor stats dari tabel users
        $donorQuery = "SELECT COUNT(*) as total_donors FROM users WHERE role = 'user'";
        $donorResult = $koneksi->query($donorQuery);
        $donorStats = $donorResult->fetch_assoc();

        // Monthly income data
        $monthlyQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total_success FROM transactions WHERE status = 'success' GROUP BY month ORDER BY month ASC";
        $monthlyResult = $koneksi->query($monthlyQuery);
        $monthlyData = [];
        while ($row = $monthlyResult->fetch_assoc()) {
            $monthlyData[] = $row;
        }

        $dailyQuery = "SELECT DATE(created_at) as date, SUM(amount) as total_success FROM transactions WHERE status = 'success' GROUP BY date ORDER BY date ASC";
        $dailyResult = $koneksi->query($dailyQuery);
        $dailyData = [];
        while ($row = $dailyResult->fetch_assoc()) {
            $dailyData[] = $row;
        }

        // Category distribution by successful donation amount
        $categoryQuery = "SELECT p.category, SUM(t.amount) as total_amount FROM transactions t JOIN programs p ON t.program_id = p.id WHERE t.status = 'success' GROUP BY p.category";
        $categoryResult = $koneksi->query($categoryQuery);
        $categoryData = [];
        while ($row = $categoryResult->fetch_assoc()) {
            $categoryData[] = $row;
        }

        $monthlyCategoryQuery = "SELECT DATE_FORMAT(t.created_at, '%Y-%m') as month, p.category, SUM(t.amount) as total_amount FROM transactions t JOIN programs p ON t.program_id = p.id WHERE t.status = 'success' GROUP BY month, p.category ORDER BY month ASC";
        $monthlyCategoryResult = $koneksi->query($monthlyCategoryQuery);
        $monthlyCategoryData = [];
        while ($row = $monthlyCategoryResult->fetch_assoc()) {
            $monthlyCategoryData[] = $row;
        }

        // Recent successful transactions with program image
        $recentQuery = "SELECT t.id, t.trx_code, t.amount, t.payment_method, t.message, t.created_at, u.name as donor_name, p.title as program_title, p.image_url as program_image FROM transactions t JOIN users u ON t.user_id = u.id JOIN programs p ON t.program_id = p.id WHERE t.status = 'success' ORDER BY t.created_at DESC LIMIT 5";
        $recentResult = $koneksi->query($recentQuery);
        $recentData = [];
        while ($row = $recentResult->fetch_assoc()) {
            $recentData[] = $row;
        }

        // Fund distribution summary
        $distributionQuery = "SELECT COALESCE(SUM(amount), 0) as total_distributed, COUNT(*) as total_distributions FROM fund_distributions";
        $distributionResult = $koneksi->query($distributionQuery);
        $distributionStats = $distributionResult->fetch_assoc();

        $monthlyDistributionQuery = "SELECT DATE_FORMAT(distributed_at, '%Y-%m') as month, SUM(amount) as total_distributed FROM fund_distributions GROUP BY month ORDER BY month ASC";
        $monthlyDistributionResult = $koneksi->query($monthlyDistributionQuery);
        $monthlyDistributionData = [];
        while ($row = $monthlyDistributionResult->fetch_assoc()) {
            $monthlyDistributionData[] = $row;
        }

        $dailyDistributionQuery = "SELECT distributed_at as date, SUM(amount) as total_distributed FROM fund_distributions GROUP BY distributed_at ORDER BY distributed_at ASC";
        $dailyDistributionResult = $koneksi->query($dailyDistributionQuery);
        $dailyDistributionData = [];
        while ($row = $dailyDistributionResult->fetch_assoc()) {
            $dailyDistributionData[] = $row;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => [
                'programs' => $programStats,
                'transactions' => $transactionStats,
                'donors' => $donorStats,
                'distributions' => $distributionStats,
                'monthly_incomes' => $monthlyData,
                'daily_incomes' => $dailyData,
                'monthly_distributions' => $monthlyDistributionData,
                'daily_distributions' => $dailyDistributionData,
                'category_distribution' => $categoryData,
                'monthly_category_distribution' => $monthlyCategoryData,
                'recent_successful_transactions' => $recentData
            ]
        ]);
    }

    // ===== GET FUND DISTRIBUTIONS =====
    else if ($action === 'distributions') {
        ensureDistributionsTable($koneksi);

        $period = $_GET['period'] ?? 'monthly';
        $month = $_GET['month'] ?? date('Y-m');
        $where = '';
        $params = [];
        $types = '';

        if ($period === 'yearly') {
            $year = preg_match('/^\d{4}/', $month) ? substr($month, 0, 4) : date('Y');
            $where = " WHERE YEAR(fd.distributed_at) = ?";
            $params[] = (int)$year;
            $types .= 'i';
        } else {
            $selectedMonth = preg_match('/^\d{4}-\d{2}$/', $month) ? $month : date('Y-m');
            $where = " WHERE DATE_FORMAT(fd.distributed_at, '%Y-%m') = ?";
            $params[] = $selectedMonth;
            $types .= 's';
        }

        $query = "SELECT
                    fd.id,
                    fd.beneficiary,
                    fd.amount,
                    fd.proof_image,
                    fd.distributed_at,
                    fd.created_at,
                    p.title as program_title
                  FROM fund_distributions fd
                  JOIN programs p ON fd.program_id = p.id
                  $where
                  ORDER BY fd.distributed_at DESC, fd.created_at DESC
                  LIMIT 20";

        $stmt = $koneksi->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare error: " . $koneksi->error);
        }

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $distributions = [];
        while ($row = $result->fetch_assoc()) {
            $distributions[] = $row;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $distributions,
            'count' => count($distributions)
        ]);
    }

    // ===== GET ALL PROGRAMS =====
    else if ($action === 'programs') {
        $query = "SELECT * FROM programs ORDER BY created_at DESC";
        $result = $koneksi->query($query);

        if (!$result) {
            throw new Exception("Query error: " . $koneksi->error);
        }

        $programs = [];
        while ($row = $result->fetch_assoc()) {
            $programs[] = $row;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $programs,
            'count' => count($programs)
        ]);
    }

    // ===== GET DONATUR USERS =====
    else if ($action === 'users') {
        $query = "SELECT id, name, email, phone, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC";
        $result = $koneksi->query($query);

        if (!$result) {
            throw new Exception("Query error: " . $koneksi->error);
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $users,
            'count' => count($users)
        ]);
    }

    // ===== CREATE ADMIN USER =====
    else if ($action === 'create_admin') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ]);
            exit;
        }

        requireSuperAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $admin_role = trim($input['admin_role'] ?? '');

        if (!$name || !$email || !$admin_role) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Nama, email, dan hak akses harus diisi'
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid'
            ]);
            exit;
        }

        $allowedRoles = ['super_admin', 'staff_keuangan', 'staff_program'];
        if (!in_array($admin_role, $allowedRoles, true)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Hak akses admin tidak valid'
            ]);
            exit;
        }

        $checkQuery = "SELECT id, role FROM users WHERE email = ?";
        $checkStmt = $koneksi->prepare($checkQuery);
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $existingUser = $checkResult->fetch_assoc();

            if ($existingUser['role'] === 'admin') {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email sudah terdaftar sebagai admin'
                ]);
                exit;
            }

            $updateQuery = "UPDATE users SET name = ?, role = 'admin', admin_role = ? WHERE id = ?";
            $updateStmt = $koneksi->prepare($updateQuery);

            if (!$updateStmt) {
                throw new Exception('Gagal menyiapkan query update admin');
            }

            $updateStmt->bind_param('ssi', $name, $admin_role, $existingUser['id']);
            if (!$updateStmt->execute()) {
                throw new Exception('Gagal mengubah role pengguna menjadi admin: ' . $updateStmt->error);
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Pengguna berhasil dipromosikan menjadi admin',
                'data' => [
                    'id' => $existingUser['id'],
                    'name' => $name,
                    'email' => $email,
                    'admin_role' => $admin_role
                ]
            ]);
            exit;
        }

        $defaultPassword = password_hash('12345', PASSWORD_BCRYPT);
        $insertQuery = "INSERT INTO users (name, email, password, role, admin_role, created_at) VALUES (?, ?, ?, 'admin', ?, NOW())";
        $insertStmt = $koneksi->prepare($insertQuery);

        if (!$insertStmt) {
            throw new Exception('Gagal menyiapkan query admin');
        }

        $insertStmt->bind_param('ssss', $name, $email, $defaultPassword, $admin_role);
        if (!$insertStmt->execute()) {
            throw new Exception('Gagal menambahkan admin baru: ' . $insertStmt->error);
        }

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Admin baru berhasil ditambahkan',
            'data' => [
                'id' => $insertStmt->insert_id,
                'name' => $name,
                'email' => $email,
                'admin_role' => $admin_role
            ]
        ]);
    }

    else if ($action === 'delete_admin') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ]);
            exit;
        }

        requireSuperAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $adminId = (int)($input['admin_id'] ?? 0);

        if (!$adminId) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'admin_id harus diisi'
            ]);
            exit;
        }

        if ($adminId === $_SESSION['user_id']) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Tidak bisa menghapus akun sendiri'
            ]);
            exit;
        }

        $deleteQuery = "DELETE FROM users WHERE id = ? AND role = 'admin'";
        $deleteStmt = $koneksi->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $adminId);

        if (!$deleteStmt->execute()) {
            throw new Exception('Gagal menghapus admin: ' . $deleteStmt->error);
        }

        if ($deleteStmt->affected_rows === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Admin tidak ditemukan atau tidak dapat dihapus'
            ]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Admin berhasil dihapus'
        ]);
    }

    // ===== GET ADMIN USERS =====
    else if ($action === 'admins') {
        $query = "SELECT id, name, email, avatar_url, created_at, admin_role FROM users WHERE role = 'admin' ORDER BY created_at DESC";
        $result = $koneksi->query($query);

        if (!$result) {
            throw new Exception("Query error: " . $koneksi->error);
        }

        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $admins,
            'count' => count($admins)
        ]);
    }
    
    // ===== UPDATE TRANSACTION STATUS =====
    else if ($action === 'verify') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['transaction_id']) || !isset($input['new_status'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'transaction_id dan new_status harus diisi'
            ]);
            exit;
        }
        
        $transaction_id = (int)$input['transaction_id'];
        $new_status = $input['new_status'];
        $admin_id = $_SESSION['user_id'];
        
        if (!in_array($new_status, ['success', 'failed'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Status tidak valid'
            ]);
            exit;
        }
        
        try {
            $koneksi->begin_transaction();
            
            // Get transaction data
            $getTrx = "SELECT * FROM transactions WHERE id = ?";
            $getTrxStmt = $koneksi->prepare($getTrx);
            $getTrxStmt->bind_param('i', $transaction_id);
            $getTrxStmt->execute();
            $trxResult = $getTrxStmt->get_result();
            
            if ($trxResult->num_rows === 0) {
                throw new Exception("Transaksi tidak ditemukan");
            }
            
            $trxData = $trxResult->fetch_assoc();
            
            $oldStatus = $trxData['status'];

            // Update transaction status
            $updateTrx = "UPDATE transactions SET status = ?, verified_by = ? WHERE id = ?";
            $updateStmt = $koneksi->prepare($updateTrx);
            $updateStmt->bind_param('sii', $new_status, $admin_id, $transaction_id);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Gagal update status transaksi");
            }
            
            // Sinkronkan collected_amount agar tidak dobel saat verifikasi ulang
            if ($oldStatus !== 'success' && $new_status === 'success') {
                $updateProgram = "UPDATE programs SET collected_amount = collected_amount + ? WHERE id = ?";
                $updateProgStmt = $koneksi->prepare($updateProgram);
                $updateProgStmt->bind_param('di', $trxData['amount'], $trxData['program_id']);
                
                if (!$updateProgStmt->execute()) {
                    throw new Exception("Gagal update program amount");
                }
            } else if ($oldStatus === 'success' && $new_status === 'failed') {
                $updateProgram = "UPDATE programs SET collected_amount = GREATEST(collected_amount - ?, 0) WHERE id = ?";
                $updateProgStmt = $koneksi->prepare($updateProgram);
                $updateProgStmt->bind_param('di', $trxData['amount'], $trxData['program_id']);

                if (!$updateProgStmt->execute()) {
                    throw new Exception("Gagal update program amount");
                }
            }
            
            $koneksi->commit();
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Status transaksi berhasil diupdate',
                'data' => [
                    'transaction_id' => $transaction_id,
                    'status' => $new_status
                ]
            ]);
            
        } catch (Exception $e) {
            $koneksi->rollback();
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$koneksi->close();
?>
