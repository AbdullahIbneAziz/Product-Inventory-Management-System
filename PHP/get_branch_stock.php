<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $branch = isset($_GET['branch']) ? $_GET['branch'] : '';
    
    if (empty($branch)) {
        throw new Exception('Branch parameter is required');
    }

    $sql = "SELECT bs.*, 
            SUM(bs.quantity) as total_quantity,
            GROUP_CONCAT(DISTINCT bs.size) as sizes
            FROM branch_stock bs
            WHERE bs.branch = :branch
            GROUP BY bs.product_id
            ORDER BY bs.product_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':branch' => $branch]);
    
    $stock = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'stock' => $stock]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 