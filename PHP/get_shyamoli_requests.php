<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/db_connect.php';

try {
    $sql = "SELECT r.*, GROUP_CONCAT(
                CONCAT(ri.product_name, ':', ri.quantity, ':', ri.size)
                SEPARATOR '|'
            ) as items
            FROM shyamoli_stock_requests r
            LEFT JOIN shyamoli_request_items ri ON r.id = ri.request_id
            WHERE r.branch = 'Shyamoli'
            GROUP BY r.id
            ORDER BY r.request_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $requests = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $items = [];
        if ($row['items']) {
            foreach (explode('|', $row['items']) as $item) {
                list($name, $quantity, $size) = explode(':', $item);
                $items[] = [
                    'name' => $name,
                    'quantity' => $quantity,
                    'size' => $size
                ];
            }
        }
        $row['items'] = $items;
        unset($row['items_str']);
        $requests[] = $row;
    }
    
    echo json_encode(['success' => true, 'requests' => $requests]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 