<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Start transaction
    $conn->beginTransaction();
    
    // Determine which table to update based on request ID prefix
    $isShyamoli = strpos($data['requestId'], 'SREQ') === 0;
    $requestTable = $isShyamoli ? 'shyamoli_stock_requests' : 'stock_requests';
    $itemsTable = $isShyamoli ? 'shyamoli_request_items' : 'request_items';
    $branch = $isShyamoli ? 'Shyamoli' : 'Banani';

    // If request is being approved, check stock availability first
    if ($data['status'] === 'approved') {
        // Get request items and check stock
        $stmt = $conn->prepare("SELECT ri.*, p.id as product_id, p.unit_price, p.quantity as available_quantity 
                               FROM $itemsTable ri
                               JOIN products p ON p.name = ri.product_name
                               WHERE ri.request_id = :request_id");
        $stmt->execute([':request_id' => $data['requestId']]);
        $items = $stmt->fetchAll();

        // Check if all items are available in sufficient quantity
        $unavailableItems = [];
        foreach ($items as $item) {
            if ($item['quantity'] > $item['available_quantity']) {
                $unavailableItems[] = [
                    'product' => $item['product_name'],
                    'requested' => $item['quantity'],
                    'available' => $item['available_quantity']
                ];
            }
        }

        // If any items are unavailable, return error
        if (!empty($unavailableItems)) {
            $conn->rollBack();
            $message = "The following products are not available in sufficient quantity:\n\n";
            foreach ($unavailableItems as $item) {
                $message .= "- {$item['product']}: Requested {$item['requested']}, Available {$item['available']}\n";
            }
            echo json_encode([
                'success' => false,
                'error' => $message
            ]);
            exit;
        }

        // Update product quantities and add to branch stock
        $updateProductStmt = $conn->prepare("UPDATE products 
                                           SET quantity = quantity - :request_quantity 
                                           WHERE id = :product_id");
        
        $insertBranchStmt = $conn->prepare("INSERT INTO branch_stock 
                                          (branch, product_id, product_name, quantity, size, unit_price, request_id) 
                                          VALUES (:branch, :product_id, :product_name, :quantity, :size, :unit_price, :request_id)");

        foreach ($items as $item) {
            // Deduct from main inventory
            $updateProductStmt->execute([
                ':request_quantity' => $item['quantity'],
                ':product_id' => $item['product_id']
            ]);

            // Add to branch stock
            $insertBranchStmt->execute([
                ':branch' => $branch,
                ':product_id' => $item['product_id'],
                ':product_name' => $item['product_name'],
                ':quantity' => $item['quantity'],
                ':size' => $item['size'],
                ':unit_price' => $item['unit_price'],
                ':request_id' => $data['requestId']
            ]);
        }
    }

    // Update request status
    $stmt = $conn->prepare("UPDATE $requestTable 
                           SET status = :status, 
                               action_date = NOW() 
                           WHERE id = :id");
    
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['requestId']
    ]);
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error updating request: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 