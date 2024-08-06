<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'site_zero_dechet';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM produits";
$result = $conn->query($sql);

$produits = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }
}

echo json_encode($produits);
$conn->close();
?>
