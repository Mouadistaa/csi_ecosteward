<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

// Récupérer la liste des produits
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Produits</title></head>
<body>
<h1>Liste des Produits</h1>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Catégorie</th>
    <th>Stock</th>
  </tr>
  <?php foreach($products as $prod): ?>
  <tr>
    <td><?= $prod['id'] ?></td>
    <td><?= htmlspecialchars($prod['name']) ?></td>
    <td><?= htmlspecialchars($prod['category']) ?></td>
    <td><?= $prod['quantity_in_stock'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<h2>Ajouter un Produit</h2>
<form method="POST" action="../actions/add_product.php">
  <label>Nom :</label>
  <input type="text" name="name" required><br><br>

  <label>Catégorie :</label>
  <input type="text" name="category" required><br><br>

  <label>Quantité :</label>
  <input type="number" name="quantity" required><br><br>

  <button type="submit">Ajouter</button>
</form>

</body>
</html>
