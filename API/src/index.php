<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "db";
$username = "user";
$password = "mdp";
$dbname = "joueur_stats_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/joueur_stats':
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                try {
                    $stmt = $conn->query("SELECT * FROM joueur_stats");
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($data);
                } catch(PDOException $e) {
                    echo "Erreur lors de la récupération des données : " . $e->getMessage();
                }
                break;

            case 'POST':
                try {
                    $data = json_decode(file_get_contents('php://input'), true);

                    if (!isset($data['nom_joueur']) || !isset($data['attaque']) || !isset($data['vie']) || !isset($data['niveau'])) {
                        http_response_code(400); // Bad Request
                        echo json_encode(['error' => 'Données invalides pour l\'ajout des statistiques du joueur']);
                        break;
                    }
            
                    $stmt = $conn->prepare("INSERT INTO joueur_stats (nom_joueur, attaque, vie, niveau) VALUES (:nom_joueur, :attaque, :vie, :niveau)");
                    $stmt->bindParam(':nom_joueur', $data['nom_joueur']);
                    $stmt->bindParam(':attaque', $data['attaque']);
                    $stmt->bindParam(':vie', $data['vie']);
                    $stmt->bindParam(':niveau', $data['niveau']);
                    $stmt->execute();
            
                    echo json_encode(['message' => 'Statistiques du joueur ajoutées avec succès']);
                } catch(PDOException $e) {
                    echo "Erreur lors de l'ajout des statistiques du joueur : " . $e->getMessage();
                }
                break;
            
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(['error' => 'Méthode non autorisée']);
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route non reconnue']);
        break;
}

?>