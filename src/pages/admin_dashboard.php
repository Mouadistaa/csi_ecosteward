<?php
session_start();

// Vérification du rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Connexion à la BDD
try {
    $pdo = new PDO('mysql:host=localhost;dbname=eco_farm;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Exécution de la requête
$stmt = $pdo->query("
    SELECT id, email, role 
    FROM users 
    ORDER BY id DESC
");

// Récupération des données
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="admin-container">
  <h1>Espace Administration</h1>
  <h2>Liste des utilisateurs</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Email</th>
      <th>Rôle</th>
      <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
          <a href="reset_admin.php?user_id=<?= $user['id'] ?>" class="action-link">
            Réinitialiser mot de passe
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p class="return-link">
    <a href="../index.php">Retour à l'accueil</a>
  </p>
</div>
<script src="../js/admin.js"></script>
</body>
</html>
