<?php

$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'site_zero_dechet';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nom = $_POST['nom'];
$email = $_POST['email'];
$service_id = $_POST['service'];
$description = $_POST['description'];

$sql = "INSERT INTO devis (nom, email, service_id, description) VALUES ('$nom', '$email', '$service_id', '$description')";

if ($conn->query($sql) === TRUE) {
    echo "Demande de devis envoyée avec succès";
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
