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
        'message' => 'Anda harus login'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get user donation history dengan detail program
    $query = "SELECT 
                t.id,
                t.trx_code,
                t.amount,
                t.payment_method,
                t.message,
                t.status,
                t.created_at,
                p.id as program_id,
                p.title as program_title,
                p.category as program_category,
                p.image_url
              FROM transactions t
              JOIN programs p ON t.program_id = p.id
              WHERE t.user_id = ?
              ORDER BY t.created_at DESC";
    
    $stmt = $koneksi->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare error: " . $koneksi->error);
    }
    
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $donations = [];
    $totalDonated = 0;
    $successCount = 0;
    
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
        
        if ($row['status'] === 'success') {
            $totalDonated += $row['amount'];
            $successCount++;
        }
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => [
            'donations' => $donations,
            'stats' => [
                'total_donated' => $totalDonated,
                'success_count' => $successCount,
                'total_count' => count($donations),
                'pending_count' => count($donations) - $successCount
            ]
        ]
    ]);
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$koneksi->close();
?>
