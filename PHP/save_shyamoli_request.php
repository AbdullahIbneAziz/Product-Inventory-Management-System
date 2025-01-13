<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/db_connect.php';

try {
    // Log received data
    $input = file_get_contents('php://input');
    error_log("Received data: " . $input);

    if (!$input) {
        throw new Exception('No input data received');
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Log decoded data
    error_log("Decoded data: " . print_r($data, true));

    // Validate required fields
    $required = ['id', 'branch', 'date', 'urgency', 'items'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }
    
    // Start transaction
    $conn->beginTransaction();
    
    // Insert into shyamoli_stock_requests table
    $sql = "INSERT INTO shyamoli_stock_requests (id, branch, request_date, urgency, notes, status) 
            VALUES (:id, :branch, :date, :urgency, :notes, 'pending')";
    error_log("SQL for main request: " . $sql);
    
    $stmt = $conn->prepare($sql);
    
    $params = [
        ':id' => $data['id'],
        ':branch' => $data['branch'],
        ':date' => $data['date'],
        ':urgency' => $data['urgency'],
        ':notes' => isset($data['notes']) ? $data['notes'] : null
    ];
    
    // Log parameters
    error_log("Parameters for main request: " . print_r($params, true));
    
    $stmt->execute($params);
    
    // Insert items into shyamoli_request_items table
    $itemSql = "INSERT INTO shyamoli_request_items (request_id, product_name, quantity, size) 
                VALUES (:request_id, :name, :quantity, :size)";
    $stmt = $conn->prepare($itemSql);
    
    foreach ($data['items'] as $item) {
        if (empty($item['name']) || empty($item['quantity']) || empty($item['size'])) {
            throw new Exception('Invalid item data');
        }
        
        $itemParams = [
            ':request_id' => $data['id'],
            ':name' => $item['name'],
            ':quantity' => intval($item['quantity']),
            ':size' => $item['size']
        ];
        
        // Log item parameters
        error_log("Parameters for item: " . print_r($itemParams, true));
        
        $stmt->execute($itemParams);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback transaction if active
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log error with full details
    error_log("Shyamoli stock request error: " . $e->getMessage());
    error_log("Error trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage(),
        'details' => 'Check server logs for more information'
    ]);
}
?> 