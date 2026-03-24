<?php
// api/contact.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = $data['vehicle_id'] ?? null;
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $message = $data['message'] ?? '';
    
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants']);
        exit();
    }
    
    if ($vehicleId) {
        // Demande de devis pour un véhicule
        $stmt = $db->prepare("INSERT INTO quote_requests (vehicle_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$vehicleId, $name, $email, $phone, $message]);
        
        // Récupérer le véhicule
        $stmt = $db->prepare("SELECT brand, model FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch();
        
        $subject = "Demande de devis - " . $vehicle['brand'] . " " . $vehicle['model'];
    } else {
        // Message de contact simple
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $phone, 'Demande d\'information', $message]);
        $subject = "Nouveau message de contact";
    }
    
    if ($result) {
        // Envoyer un email de notification
        $to = "contact@automarket.fr";
        $emailBody = "Nouveau message de : $name\n";
        $emailBody .= "Email : $email\n";
        $emailBody .= "Téléphone : $phone\n\n";
        $emailBody .= "Message :\n$message\n";
        
        mail($to, $subject, $emailBody, "From: $email");
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi']);
    }
}
?>