<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $workshop_id = $_POST['workshop_id'] ?? 0;
  $name = $_POST['participant_name'] ?? '';
  $contact = $_POST['participant_contact'] ?? '';

  $stmt = $pdo->prepare("INSERT INTO workshop_registrations (workshop_id, participant_name, participant_contact)
                         VALUES (:w, :pn, :pc)");
  $stmt->execute(['w' => $workshop_id, 'pn' => $name, 'pc' => $contact]);

  // Possibilité de vérifier la capacité et basculer en liste d'attente
  // si inscriptions >= capacity

  header('Location: ../pages/workshops.php');
  exit;
}
