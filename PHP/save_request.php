<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/db_connect.php';

try {
    // Get and validate input data
    $input = file_get_contents('php://input');
    if (!$input) {
        throw new Exception('No input data received');
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Validate required fields
    $required = ['id', 'branch', 'date', 'urgency', 'items'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }
    
    // Start transaction
    $conn->beginTransaction();
    
    // Insert into stock_requests table
    $stmt = $conn->prepare("INSERT INTO stock_requests (id, branch, request_date, urgency, notes, status) 
                           VALUES (:id, :branch, :date, :urgency, :notes, 'pending')");
    
    $stmt->execute([
        ':id' => $data['id'],
        ':branch' => $data['branch'],
        ':date' => $data['date'],
        ':urgency' => $data['urgency'],
        ':notes' => isset($data['notes']) ? $data['notes'] : null
    ]);
    
    // Insert items into request_items table
    $stmt = $conn->prepare("INSERT INTO request_items (request_id, product_name, quantity, size) 
                           VALUES (:request_id, :name, :quantity, :size)");
    
    foreach ($data['items'] as $item) {
        if (empty($item['name']) || empty($item['quantity']) || empty($item['size'])) {
            throw new Exception('Invalid item data');
        }
        
        $stmt->execute([
            ':request_id' => $data['id'],
            ':name' => $item['name'],
            ':quantity' => intval($item['quantity']),
            ':size' => $item['size']
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction if active
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log error
    error_log("Stock request error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage(),
        'details' => 'Check server logs for more information'
    ]);
}
?> 