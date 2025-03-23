<?php
session_start();

// Vérifier qu'on est bien admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Connexion à la base
try {
    $pdo = new PDO('mysql:host=localhost;dbname=eco_farm;charset=utf8','root','', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérifier qu'un user_id est présent dans l'URL
if (!isset($_GET['user_id'])) {
    die("User ID non spécifié.");
}
$userId = (int) $_GET['user_id'];

// Récupérer l'utilisateur
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// S'il n'y a pas d'utilisateur correspondant
if (!$user) {
    die("Utilisateur introuvable.");
}

// On initialise une variable $message vide pour éviter le warning
$message = "";

// Traitement du formulaire si on fait un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Met à jour le mot de passe en SHA2
        $update = $pdo->prepare("
            UPDATE users
            SET password_hash = SHA2(:pwd, 256)
            WHERE id = :id
        ");
        $update->execute([
            ':pwd' => $newPassword,
            ':id' => $userId
        ]);

        $message = "Le mot de passe de " . htmlspecialchars($user['email']) 
                 . " a été réinitialisé avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialiser mot de passe</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="admin-container">
  <h1>Réinitialiser le mot de passe</h1>
  <h2>pour : <?= htmlspecialchars($user['email']) ?></h2>

  <?php if ($message): ?>
    <p style="color: green;"><?= $message ?></p>
  <?php endif; ?>

  <div class="reset-form">
    <form method="POST">
      <label for="new_password">Nouveau mot de passe :</label>
      <input type="password" name="new_password" id="new_password" required>

      <label for="confirm_password">Confirmer le mot de passe :</label>
      <input type="password" name="confirm_password" id="confirm_password" required>

      <button type="submit">Valider</button>
    </form>
  </div>

  <p class="return-link">
    <a href="admin_dashboard.php">← Retour au Dashboard</a>
  </p>
</div>
<script src="../js/admin.js"></script>
</body>
</html>
