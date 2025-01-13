<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception('Invalid input data');
    }

    // Start transaction
    $conn->beginTransaction();

    // Check if enough stock is available
    $checkStockSql = "SELECT SUM(quantity) as total_quantity 
                      FROM branch_stock 
                      WHERE branch = :branch 
                      AND product_id = :product_id";
    
    $checkStmt = $conn->prepare($checkStockSql);
    $checkStmt->execute([
        ':branch' => $data['outlet'],
        ':product_id' => $data['productId']
    ]);
    
    $stockResult = $checkStmt->fetch();
    
    if ($stockResult['total_quantity'] < $data['quantity']) {
        throw new Exception('Insufficient stock available');
    }

    // Insert into sales table
    $salesSql = "INSERT INTO sales (sale_date, product_id, product_name, quantity, unit_price, net_price, outlet) 
                 VALUES (:sale_date, :product_id, :product_name, :quantity, :unit_price, :net_price, :outlet)";
    
    $salesStmt = $conn->prepare($salesSql);
    $salesStmt->execute([
        ':sale_date' => $data['date'],
        ':product_id' => $data['productId'],
        ':product_name' => $data['productName'],
        ':quantity' => $data['quantity'],
        ':unit_price' => $data['price'],
        ':net_price' => $data['netPrice'],
        ':outlet' => $data['outlet']
    ]);

    // Update branch stock
    $updateStockSql = "UPDATE branch_stock 
                       SET quantity = quantity - :sold_quantity 
                       WHERE branch = :branch 
                       AND product_id = :product_id
                       AND quantity > 0
                       LIMIT 1";
    
    $updateStmt = $conn->prepare($updateStockSql);
    $updateStmt->execute([
        ':sold_quantity' => $data['quantity'],
        ':branch' => $data['outlet'],
        ':product_id' => $data['productId']
    ]);

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Sale recorded successfully']);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 