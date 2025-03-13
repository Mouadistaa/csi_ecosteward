<?php
try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=eco_farm;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Données de l'admin
    $email = "admin@farm.com";
    $motDePasse = "admin123";
    $role = "admin";

    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    
    if ($stmt->fetch()) {
        echo "L'utilisateur admin existe déjà.";
        exit;
    }

    // Hashage du mot de passe
    $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

    // Insérer dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (:email, :password, :role)");
    $stmt->execute([
        ':email' => $email,
        ':password' => $hash,
        ':role' => $role
    ]);

    echo "✅ Admin ajouté avec succès !";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>
