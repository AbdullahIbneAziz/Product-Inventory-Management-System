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

    // Get the original sale details
    $getOriginalSql = "SELECT product_id, quantity FROM sales WHERE id = :id";
    $getOriginalStmt = $conn->prepare($getOriginalSql);
    $getOriginalStmt->execute([':id' => $data['id']]);
    $originalSale = $getOriginalStmt->fetch();

    if (!$originalSale) {
        throw new Exception('Original sale record not found');
    }

    // Restore the original quantity to stock
    $restoreStockSql = "UPDATE branch_stock 
                        SET quantity = quantity + :quantity 
                        WHERE branch = :branch 
                        AND product_id = :product_id";
    
    $restoreStmt = $conn->prepare($restoreStockSql);
    $restoreStmt->execute([
        ':quantity' => $originalSale['quantity'],
        ':branch' => $data['outlet'],
        ':product_id' => $originalSale['product_id']
    ]);

    // Check if enough stock is available for the new quantity
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
        throw new Exception('Insufficient stock available for the new quantity');
    }

    // Update the sales record
    $updateSaleSql = "UPDATE sales 
                      SET product_id = :product_id,
                          product_name = :product_name,
                          quantity = :quantity,
                          unit_price = :unit_price,
                          net_price = :net_price
                      WHERE id = :id";
    
    $updateSaleStmt = $conn->prepare($updateSaleSql);
    $updateSaleStmt->execute([
        ':id' => $data['id'],
        ':product_id' => $data['productId'],
        ':product_name' => $data['productName'],
        ':quantity' => $data['quantity'],
        ':unit_price' => $data['unitPrice'],
        ':net_price' => $data['netPrice']
    ]);

    // Update the stock for the new quantity
    $updateStockSql = "UPDATE branch_stock 
                       SET quantity = quantity - :quantity 
                       WHERE branch = :branch 
                       AND product_id = :product_id";
    
    $updateStockStmt = $conn->prepare($updateStockSql);
    $updateStockStmt->execute([
        ':quantity' => $data['quantity'],
        ':branch' => $data['outlet'],
        ':product_id' => $data['productId']
    ]);

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Sale updated successfully']);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 