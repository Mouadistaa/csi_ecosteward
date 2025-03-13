<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $category = $_POST['category'] ?? '';
  $quantity = $_POST['quantity'] ?? 0;

  $stmt = $pdo->prepare("INSERT INTO products (name, category, quantity_in_stock)
                         VALUES (:n, :c, :q)");
  $stmt->execute(['n' => $name, 'c' => $category, 'q' => $quantity]);

  header('Location: ../pages/products.php');
  exit;
}
