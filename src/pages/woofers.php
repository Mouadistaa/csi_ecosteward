<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Afficher tous les woofers si admin
if ($_SESSION['role'] === 'admin') {
  $stmt = $pdo->query("SELECT * FROM woofers");
  $woofers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // Un woofer ne voit que ses infos (?)
  // adapt if needed
  $woofers = [];
}

?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Woofers</title></head>
<body>
<h1>Liste des Woofers</h1>
<ul>
  <?php foreach($woofers as $w): ?>
  <li><?= htmlspecialchars($w['name']) ?> (<?= $w['email'] ?>)</li>
  <?php endforeach; ?>
</ul>

<?php if ($_SESSION['role'] === 'admin'): ?>
<h2>Ajouter un Woofer</h2>
<form method="POST" action="../actions/add_woofer.php">
  <label>Nom :</label>
  <input type="text" name="name" required><br>
  <label>Email :</label>
  <input type="email" name="email" required><br>
  <label>Date Début :</label>
  <input type="date" name="start_date"><br>
  <label>Date Fin :</label>
  <input type="date" name="end_date"><br>
  <button type="submit">+ Add Woofer</button>
</form>
<?php endif; ?>

</body>
</html>
