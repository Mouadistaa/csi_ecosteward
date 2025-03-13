<?php
$host = 'localhost';       // Ou l'hôte de la DB (ex. 127.0.0.1)
$dbname = 'eco_farm';      // Nom de la base
$user = 'root';            // Par défaut XAMPP
$pass = '';                // Par défaut XAMPP

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}
?>
