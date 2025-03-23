<?php
$host = 'localhost';
$db   = 'eco_farm';
$user = 'root';
$pass = ''; // vide par défaut sous XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base : " . $e->getMessage();
    exit;
}
?>