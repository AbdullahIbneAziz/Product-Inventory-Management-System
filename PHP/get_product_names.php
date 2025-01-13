<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $sql = "SELECT DISTINCT name FROM products ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode(['success' => true, 'products' => $products]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 