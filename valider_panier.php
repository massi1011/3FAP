<?php
header('Content-Type: application/json');

$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'site_zero_dechet';

// Crée une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupère les données du panier envoyées en POST
$panier = json_decode(file_get_contents('php://input'), true);

// Vérifie si le panier n'est pas vide
if (empty($panier)) {
    echo json_encode(['message' => 'Le panier est vide.']);
    $conn->close();
    exit;
}

// Prépare une déclaration SQL pour insérer les données de la commande
$stmt = $conn->prepare("INSERT INTO commandes (produit_id, quantite, prix_total) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['message' => 'Erreur de préparation de la déclaration SQL.']);
    $conn->close();
    exit;
}

$totalPrix = 0;

foreach ($panier as $item) {
    $id = $item['id'];
    $quantite = $item['quantite'];
    $prix = $item['prix'];
    $prixTotal = $prix * $quantite;
    
    // Exécute la déclaration pour chaque produit du panier
    $stmt->bind_param("iid", $id, $quantite, $prixTotal);
    $stmt->execute();
    
    $totalPrix += $prixTotal;
}

// Ferme la déclaration
$stmt->close();

// Ferme la connexion
$conn->close();

// Répond au client avec un message de succès
echo json_encode(['message' => 'Panier validé avec succès. Prix total: ' . number_format($totalPrix, 2) . '€']);
?>
