<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $date = $_POST['date'] ?? '';
  $theme = $_POST['theme'] ?? '';
  $capacity = $_POST['capacity'] ?? 10;
  $woofer_animator_id = $_POST['woofer_animator_id'] ?? null;

  $stmt = $pdo->prepare("INSERT INTO workshops (title, date, theme, capacity, woofer_animator_id)
                         VALUES (:t, :d, :th, :c, :wa)");
  $stmt->execute([
    't' => $title,
    'd' => $date,
    'th' => $theme,
    'c' => $capacity,
    'wa' => $woofer_animator_id
  ]);

  header('Location: ../pages/workshops.php');
  exit;
}
