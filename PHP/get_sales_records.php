<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $outlet = isset($_GET['outlet']) ? $_GET['outlet'] : null;
    
    $query = "SELECT * FROM sales WHERE 1=1";
    if ($outlet) {
        $query .= " AND outlet = :outlet";
    }
    $query .= " ORDER BY sale_date DESC";
    
    $stmt = $conn->prepare($query);
    if ($outlet) {
        $stmt->bindParam(':outlet', $outlet);
    }
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'sales' => $sales]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 