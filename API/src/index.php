<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Connexion à la base de données
$servername = "db";
$username = "db_user";
$password = "db_password";
$dbname = "forum_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

// Récupération des données à partir de la base de données en fonction de l'URL demandée
$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/':
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                try {
                    // Exécutez la requête SQL pour récupérer toutes les données
                    $stmt = $conn->query("SELECT * FROM messages");

                    // Récupérez toutes les lignes de résultat sous forme d'array associatif
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Affichez les données récupérées au format JSON
                    echo json_encode($data);
                } catch(PDOException $e) {
                    echo "Erreur lors de la récupération des données : " . $e->getMessage();
                }
                break;

            case 'POST':
                try {
                    // Récupérez les données de la requête POST
                    $data = json_decode(file_get_contents('php://input'), true);
            
                    // Vérifiez si les données nécessaires sont présentes
                    if (!isset($data['user']) || !isset($data['description'])) {
                        http_response_code(400); // Bad Request
                        echo json_encode(['error' => 'Données invalides pour l\'ajout du message']);
                        break;
                    }
            
                    // Exécutez la requête SQL pour insérer les données
                    $stmt = $conn->prepare("INSERT INTO messages (user, description) VALUES (:user, :description)");
                    $stmt->bindParam(':user', $data['user']);
                    $stmt->bindParam(':description', $data['description']);
                    $stmt->execute();
            
                    // Affichez un message de succès
                    echo json_encode(['message' => 'Message ajoute avec succes']);
                } catch(PDOException $e) {
                    echo "Erreur lors de l'ajout du message : " . $e->getMessage();
                }
                break;
            
            case 'DELETE':
                try {
                    // Récupérez les données de la requête DELETE
                    $data = json_decode(file_get_contents('php://input'), true);
            
                    // Vérifiez si les données nécessaires sont présentes
                    if (!isset($data['id'])) {
                        http_response_code(400); // Bad Request
                        echo json_encode(['error' => 'Données invalides pour la suppression du message']);
                        break;
                    }
            
                    // Exécutez la requête SQL pour supprimer les données
                    $stmt = $conn->prepare("DELETE FROM messages WHERE id = :id");
                    $stmt->bindParam(':id', $data['id']);
                    $stmt->execute();
            
                    // Affichez un message de succès
                    echo json_encode(['message' => 'Message supprime avec succes']);
                } catch(PDOException $e) {
                    echo "Erreur lors de la suppression du message : " . $e->getMessage();
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
