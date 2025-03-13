<?php
session_start();
require_once '../includes/db_connect.php';

// tout le monde peut voir, l'admin peut creer
$stmt = $pdo->query("SELECT * FROM workshops ORDER BY date ASC");
$workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Workshops</title></head>
<body>
<h1>Liste des Ateliers</h1>
<ul>
  <?php foreach($workshops as $wk): ?>
  <li><?= htmlspecialchars($wk['title']) ?> (<?= $wk['date'] ?>) - Capacité : <?= $wk['capacity'] ?></li>
  <?php endforeach; ?>
</ul>

<?php if ($_SESSION['role'] === 'admin'): ?>
<h2>Nouvel Atelier</h2>
<form method="POST" action="../actions/add_workshop.php">
  <label>Titre :</label>
  <input type="text" name="title" required><br>
  <label>Date :</label>
  <input type="date" name="date" required><br>
  <label>Thème :</label>
  <input type="text" name="theme"><br>
  <label>Capacité :</label>
  <input type="number" name="capacity" value="10"><br>
  <label>Woofer Animateur (ID) :</label>
  <input type="number" name="woofer_animator_id"><br>
  <button type="submit">+ New Workshop</button>
</form>
<?php endif; ?>
</body>
</html>
