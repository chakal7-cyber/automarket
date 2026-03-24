<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Gestion de la méthode GET (affichage des véhicules)
if ($method === 'GET') {
    $sql = "SELECT * FROM rental_vehicles WHERE available = 1";
    $params = [];
    
    // Filtre par marque
    if (isset($_GET['brand']) && $_GET['brand'] != '') {
        $sql .= " AND brand = ?";
        $params[] = $_GET['brand'];
    }
    
    // Filtre par carburant
    if (isset($_GET['fuel']) && $_GET['fuel'] != '') {
        $sql .= " AND fuel = ?";
        $params[] = $_GET['fuel'];
    }
    
    // Filtre par nombre de places
    if (isset($_GET['seats']) && $_GET['seats'] != '') {
        $sql .= " AND seats = ?";
        $params[] = $_GET['seats'];
    }
    
    // Filtre par prix
    if (isset($_GET['min_price']) && isset($_GET['max_price']) && $_GET['min_price'] != '' && $_GET['max_price'] != '') {
        $sql .= " AND price_per_day BETWEEN ? AND ?";
        $params[] = $_GET['min_price'];
        $params[] = $_GET['max_price'];
    }
    
    $sql .= " ORDER BY featured DESC, price_per_day ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // S'assurer que les images ont une URL valide
        foreach ($vehicles as &$car) {
            if (empty($car['image_url'])) {
                $car['image_url'] = 'https://via.placeholder.com/400x250?text=AutoMarket';
            }
        }
        
        echo json_encode($vehicles);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
// Gestion de la méthode POST (réservation)
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Aucune donnée reçue']);
        exit();
    }
    
    // Vérifier les champs obligatoires
    if (empty($data['vehicle_id']) || empty($data['start_date']) || empty($data['end_date']) || 
        empty($data['name']) || empty($data['email']) || empty($data['phone'])) {
        echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants']);
        exit();
    }
    
    try {
        // Calculer le nombre de jours
        $start = new DateTime($data['start_date']);
        $end = new DateTime($data['end_date']);
        $days = $end->diff($start)->days;
        
        if ($days <= 0) {
            echo json_encode(['success' => false, 'message' => 'La date de fin doit être après la date de début']);
            exit();
        }
        
        // Vérifier si le véhicule existe
        $stmt = $db->prepare("SELECT id FROM rental_vehicles WHERE id = ? AND available = 1");
        $stmt->execute([$data['vehicle_id']]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Véhicule non disponible']);
            exit();
        }
        
        // Insérer la réservation
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        $sql = "INSERT INTO rentals (user_id, vehicle_id, start_date, end_date, total_days, total_price, 
                options_gps, options_baby_seat, options_insurance, customer_name, customer_email, customer_phone, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $userId,
            $data['vehicle_id'],
            $data['start_date'],
            $data['end_date'],
            $days,
            $data['total_price'],
            isset($data['gps']) ? $data['gps'] : 0,
            isset($data['baby_seat']) ? $data['baby_seat'] : 0,
            isset($data['insurance']) ? $data['insurance'] : 0,
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['message'] ?? ''
        ]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Réservation confirmée', 'id' => $db->lastInsertId()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}
// Méthode non autorisée
else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>