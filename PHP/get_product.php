<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $productId = isset($_GET['id']) ? $_GET['id'] : '';
    
    if (empty($productId)) {
        throw new Exception('Product ID is required');
    }

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    
    $product = $stmt->fetch();
    
    if (!$product) {
        throw new Exception('Product not found');
    }
    
    echo json_encode(['success' => true, 'product' => $product]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 