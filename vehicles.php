<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM vehicles WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $sql = "SELECT * FROM vehicles WHERE status = 'available'";
            $params = [];
            
            if (isset($_GET['brand']) && $_GET['brand'] != '') {
                $sql .= " AND brand = ?";
                $params[] = $_GET['brand'];
            }
            if (isset($_GET['fuel']) && $_GET['fuel'] != '') {
                $sql .= " AND fuel = ?";
                $params[] = $_GET['fuel'];
            }
            if (isset($_GET['transmission']) && $_GET['transmission'] != '') {
                $sql .= " AND transmission = ?";
                $params[] = $_GET['transmission'];
            }
            if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                $sql .= " AND price BETWEEN ? AND ?";
                $params[] = $_GET['min_price'];
                $params[] = $_GET['max_price'];
            }
            if (isset($_GET['featured']) && $_GET['featured'] == 1) {
                $sql .= " AND featured = 1";
            }
            
            $sort = $_GET['sort'] ?? 'price_asc';
            switch($sort) {
                case 'price_asc': $sql .= " ORDER BY price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY price DESC"; break;
                case 'year_desc': $sql .= " ORDER BY year DESC"; break;
                default: $sql .= " ORDER BY id DESC";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
}
?>