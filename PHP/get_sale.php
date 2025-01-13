<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $saleId = isset($_GET['id']) ? $_GET['id'] : null;
    
    if (!$saleId) {
        throw new Exception('Sale ID is required');
    }

    $sql = "SELECT s.*, bs.unit_price 
            FROM sales s
            JOIN branch_stock bs ON s.product_id = bs.product_id 
            WHERE s.id = :id 
            AND bs.branch = s.outlet
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $saleId]);
    
    $sale = $stmt->fetch();
    
    if (!$sale) {
        throw new Exception('Sale not found');
    }

    echo json_encode(['success' => true, 'sale' => $sale]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 