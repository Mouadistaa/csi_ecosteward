<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=eco_farm;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur dans la base
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php"); // Rediriger vers le tableau de bord
        exit;
    } else {
        // Au lieu d'un echo inline, on stocke ce message d'erreur
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
