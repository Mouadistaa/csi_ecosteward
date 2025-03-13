<?php
session_start();
require_once '../includes/db_connect.php';

// Vérifier si admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

// Calculs simples
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalSales = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
$totalWoofers = $pdo->query("SELECT COUNT(*) FROM woofers")->fetchColumn();
$totalWorkshops = $pdo->query("SELECT COUNT(*) FROM workshops")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Dashboard</title></head>
<body>
<h1>Tableau de bord (Admin)</h1>
<p>Total Produits : <?= $totalProducts ?></p>
<p>Total Ventes : <?= $totalSales ?></p>
<p>Total Woofers : <?= $totalWoofers ?></p>
<p>Total Ateliers : <?= $totalWorkshops ?></p>

<!-- Exemple de liens -->
<p><a href="products.php">Voir la liste des produits</a></p>
<p><a href="sales.php">Voir les ventes</a></p>
<p><a href="woofers.php">Voir les woofers</a></p>
<p><a href="workshops.php">Voir les ateliers</a></p>
</body>
</html>
