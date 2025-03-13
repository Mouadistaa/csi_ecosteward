<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $start_date = $_POST['start_date'] ?? null;
  $end_date = $_POST['end_date'] ?? null;

  // Insérer dans table woofers
  $stmt = $pdo->prepare("INSERT INTO woofers (name, email, start_date, end_date)
                         VALUES (:n, :e, :sd, :ed)");
  $stmt->execute([
    'n' => $name,
    'e' => $email,
    'sd' => $start_date,
    'ed' => $end_date
  ]);
  $wooferID = $pdo->lastInsertId();

  // Créer un user (role=woofer) => mot de passe par défaut ?
  $defaultPass = password_hash('secret', PASSWORD_BCRYPT);
  $stmt2 = $pdo->prepare("INSERT INTO users (email, password_hash, role, woofer_id)
                          VALUES (:em, :ph, 'woofer', :wid)");
  $stmt2->execute([
    'em' => $email,
    'ph' => $defaultPass,
    'wid' => $wooferID
  ]);

  header('Location: ../pages/woofers.php');
  exit;
}
