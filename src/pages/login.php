<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=eco_farm;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier email ET mot de passe SHA2 directement dans la requête
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password_hash = SHA2(:password, 256)");
    $stmt->execute([
        ':email' => $email,
        ':password' => $password
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit;
    } else {
        $errorMsg = "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>EcoSteward – Connexion</title>
  <link rel="stylesheet" href="../css/login.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>

<div class="login-container">
  <h1>EcoSteward</h1>
  <p class="subtitle">Gestion de ferme écoresponsable</p>

  <?php if (!empty($errorMsg)): ?>
    <div class="error-msg"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <form id="loginForm" action="login.php" method="POST">
    <label for="email">Adresse email</label>
    <input type="email" name="email" id="email" placeholder="exemple@domaine.com" required />

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="********" required />

    <button type="submit">Se connecter</button>
  </form>
</div>

<script src="../js/login.js"></script>
</body>
</html>
