<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("DELETE FROM sales WHERE id = :id AND outlet = :outlet");
    $stmt->execute([
        ':id' => $data['id'],
        ':outlet' => $data['outlet']
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 