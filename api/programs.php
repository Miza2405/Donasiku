<?php
header('Content-Type: application/json');
require_once '../koneksi.php';

// Tangkap parameter dari query string
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    // ===== GET ALL PROGRAMS =====
    if ($action === 'list') {
        $query = "SELECT * FROM programs ORDER BY created_at DESC";
        $result = $koneksi->query($query);
        
        if (!$result) {
            throw new Exception("Query error: " . $koneksi->error);
        }
        
        $programs = [];
        while ($row = $result->fetch_assoc()) {
            // Hitung persentase donasi
            $percentage = ($row['target_amount'] > 0) 
                ? round(($row['collected_amount'] / $row['target_amount']) * 100, 2)
                : 0;
            
            $row['percentage'] = $percentage;
            $programs[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $programs,
            'count' => count($programs)
        ]);
    }
    
    // ===== GET PROGRAM DETAIL =====
    else if ($action === 'detail' && $id) {
        $query = "SELECT * FROM programs WHERE id = ?";
        $stmt = $koneksi->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Prepare error: " . $koneksi->error);
        }
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Program tidak ditemukan'
            ]);
            exit;
        }
        
        $program = $result->fetch_assoc();
        
        // Hitung persentase & donor count
        $percentage = ($program['target_amount'] > 0) 
            ? round(($program['collected_amount'] / $program['target_amount']) * 100, 2)
            : 0;
        
        $program['percentage'] = $percentage;
        
        // Get donor count
        $donorQuery = "SELECT COUNT(DISTINCT user_id) as donor_count FROM transactions WHERE program_id = ? AND status = 'success'";
        $donorStmt = $koneksi->prepare($donorQuery);
        $donorStmt->bind_param('i', $id);
        $donorStmt->execute();
        $donorResult = $donorStmt->get_result();
        $donorData = $donorResult->fetch_assoc();
        
        $program['donor_count'] = $donorData['donor_count'];
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $program
        ]);
        
        $stmt->close();
    }
    
    // ===== GET PROGRAM BY CATEGORY =====
    else if ($action === 'category' && isset($_GET['category'])) {
        $category = $_GET['category'];
        $validCategories = ['jariyah', 'yatim', 'pangan', 'darurat'];
        
        if (!in_array($category, $validCategories)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Kategori tidak valid'
            ]);
            exit;
        }
        
        $query = "SELECT * FROM programs WHERE category = ? ORDER BY created_at DESC";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $programs = [];
        while ($row = $result->fetch_assoc()) {
            $percentage = ($row['target_amount'] > 0) 
                ? round(($row['collected_amount'] / $row['target_amount']) * 100, 2)
                : 0;
            $row['percentage'] = $percentage;
            $programs[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $programs,
            'count' => count($programs)
        ]);
        
        $stmt->close();
    }
    
    // ===== GET ACTIVE PROGRAMS ONLY =====
    else if ($action === 'active') {
        $query = "SELECT * FROM programs WHERE status = 'active' ORDER BY created_at DESC";
        $result = $koneksi->query($query);
        
        $programs = [];
        while ($row = $result->fetch_assoc()) {
            $percentage = ($row['target_amount'] > 0) 
                ? round(($row['collected_amount'] / $row['target_amount']) * 100, 2)
                : 0;
            $row['percentage'] = $percentage;
            $programs[] = $row;
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $programs,
            'count' => count($programs)
        ]);
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
