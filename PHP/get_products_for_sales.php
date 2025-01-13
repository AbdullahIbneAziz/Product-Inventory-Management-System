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

    // Get products with their current stock quantities
    $sql = "SELECT 
            bs.product_id as id,
            bs.product_name as name,
            bs.unit_price,
            SUM(bs.quantity) as available_quantity,
            GROUP_CONCAT(DISTINCT bs.size) as sizes
            FROM branch_stock bs
            WHERE bs.branch = :branch
            GROUP BY bs.product_id, bs.product_name, bs.unit_price
            HAVING SUM(bs.quantity) >= 0
            ORDER BY bs.product_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':branch' => $branch]);
    
    $products = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'products' => $products]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 