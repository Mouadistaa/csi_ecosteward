<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Si admin, on montre toutes les ventes, sinon on montre que celles du woofer
if ($_SESSION['role'] === 'admin') {
  $stmt = $pdo->query("SELECT * FROM sales ORDER BY sale_date DESC");
} else {
  // woofer -> filtrer
  $woofer_id = $_SESSION['user_id']; // simplification
  // ou il faudrait un lien users->woofer
  $stmt = $pdo->prepare("SELECT * FROM sales WHERE woofer_id = ?");
  $stmt->execute([$woofer_id]);
}
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Ventes</title></head>
<body>
<h1>Liste des ventes</h1>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Date</th>
    <th>Woofer</th>
  </tr>
  <?php foreach($sales as $sale): ?>
  <tr>
    <td><?= $sale['id'] ?></td>
    <td><?= $sale['sale_date'] ?></td>
    <td><?= $sale['woofer_id'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<h2>Nouvelle Vente</h2>
<form method="POST" action="../actions/add_sale.php">
  <label>Woofer ID :</label>
  <input type="number" name="woofer_id" required><br><br>

  <!-- tu peux ajouter un champ pour le(s) produit(s) et quantités via JS ou input multiples -->
  <label>ID produit :</label>
  <input type="number" name="product_id" required>
  <label>Quantité :</label>
  <input type="number" name="quantity" required><br><br>

  <button type="submit">$ New Sale</button>
</form>
</body>
</html>
