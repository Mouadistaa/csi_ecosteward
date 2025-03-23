<?php
require_once __DIR__ . '/../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les champs depuis le <form>
    // Adapte les noms en fonction de ton formulaire
    $title         = $_POST['title'] ?? '';
    $workshop_date = $_POST['workshop_date'] ?? '';
    $animator_id   = $_POST['animator_id'] ?? null;  // référence à un woofer
    $capacity      = $_POST['capacity'] ?? 10;

    // Insérer l'atelier
    $stmt = $pdo->prepare("
        INSERT INTO workshops (title, workshop_date, animator_id, capacity)
        VALUES (:t, :d, :aid, :c)
    ");
    $stmt->execute([
      ':t'   => $title,
      ':d'   => $workshop_date,
      ':aid' => $animator_id,
      ':c'   => $capacity
    ]);

    // Rediriger vers la page "Ateliers"
    header('Location: ../index.php?page=ateliers');
    exit;
}
