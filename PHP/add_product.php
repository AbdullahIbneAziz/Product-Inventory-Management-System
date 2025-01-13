<?php
header('Content-Type: application/json');

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_connect.php';

try {
    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $required_fields = ['id', 'name', 'quantity', 'unitPrice', 'supplier', 'lastUpdated', 'outlet'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
            throw new Exception("Missing or empty required field: {$field}");
        }
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO products (id, name, quantity, unit_price, supplier, last_updated, outlet) 
                           VALUES (:id, :name, :quantity, :unitPrice, :supplier, :lastUpdated, :outlet)");

    // Bind parameters
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':unitPrice', $data['unitPrice']);
    $stmt->bindParam(':supplier', $data['supplier']);
    $stmt->bindParam(':lastUpdated', $data['lastUpdated']);
    $stmt->bindParam(':outlet', $data['outlet']);

    // Execute the statement
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch(Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?> 