<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($data['id']) || !isset($data['quantity']) || !isset($data['unit_price'])) {
        throw new Exception('Missing required fields');
    }

    if ($data['quantity'] < 0) {
        throw new Exception('Quantity cannot be negative');
    }

    if ($data['unit_price'] < 0) {
        throw new Exception('Unit price cannot be negative');
    }

    // Get current product data
    $stmt = $conn->prepare("SELECT quantity, unit_price FROM products WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $currentProduct = $stmt->fetch();

    if (!$currentProduct) {
        throw new Exception('Product not found');
    }

    // Start transaction
    $conn->beginTransaction();

    // Update product
    $stmt = $conn->prepare("UPDATE products 
                           SET quantity = :quantity,
                               unit_price = :unit_price,
                               last_updated = NOW()
                           WHERE id = :id");
    
    $stmt->execute([
        ':id' => $data['id'],
        ':quantity' => $data['quantity'],
        ':unit_price' => $data['unit_price']
    ]);

    // Only log if inventory_log table exists
    try {
        $stmt = $conn->prepare("SHOW TABLES LIKE 'inventory_log'");
        $stmt->execute();
        $tableExists = $stmt->rowCount() > 0;

        if ($tableExists) {
            // Calculate changes
            $quantityChange = $data['quantity'] - $currentProduct['quantity'];
            $priceChange = $data['unit_price'] - $currentProduct['unit_price'];

            // Log the update
            $stmt = $conn->prepare("INSERT INTO inventory_log 
                                  (product_id, action_type, quantity_change, price_change, action_date)
                                  VALUES (:product_id, 'update', :quantity_change, :price_change, NOW())");
            
            $stmt->execute([
                ':product_id' => $data['id'],
                ':quantity_change' => $quantityChange,
                ':price_change' => $priceChange
            ]);
        }
    } catch (Exception $e) {
        // Ignore logging errors
        error_log("Logging failed: " . $e->getMessage());
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 