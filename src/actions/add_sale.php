<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $woofer_id = $_POST['woofer_id'] ?? 0;
  $product_id = $_POST['product_id'] ?? 0;
  $quantity = $_POST['quantity'] ?? 1;

  // Créer la vente
  $stmt = $pdo->prepare("INSERT INTO sales (woofer_id) VALUES (:w)");
  $stmt->execute(['w' => $woofer_id]);
  $sale_id = $pdo->lastInsertId();

  // Lier le produit à la vente (sale_items)
  $stmt = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity) 
                         VALUES (:s, :p, :q)");
  $stmt->execute(['s' => $sale_id, 'p' => $product_id, 'q' => $quantity]);

  // Décrémenter le stock
  $stmt = $pdo->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock - :q WHERE id = :pid");
  $stmt->execute(['q' => $quantity, 'pid' => $product_id]);

  header('Location: ../pages/sales.php');
  exit;
}
