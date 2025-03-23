<?php
require_once __DIR__ . '/../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer les champs du formulaire
    // (Adapte les noms de champs selon ton <form>)
    $user_id     = $_POST['user_id']     ?? 0;   // l’ID du user/woofer qui vend
    $product_id  = $_POST['product_id']  ?? 0;
    $quantity    = $_POST['quantity']    ?? 1;
    $prix_unitaire = $_POST['prix_unitaire'] ?? 0;

    // Insérer la vente
    $stmt = $pdo->prepare("
        INSERT INTO sales (user_id, product_id, quantity, sale_date, prix_unitaire)
        VALUES (:uid, :pid, :qty, NOW(), :pu)
    ");
    $stmt->execute([
      ':uid' => $user_id,
      ':pid' => $product_id,
      ':qty' => $quantity,
      ':pu'  => $prix_unitaire
    ]);

    // Mettre à jour le stock
    $stmt = $pdo->prepare("
        UPDATE products
        SET stock = stock - :q
        WHERE id = :pid
    ");
    $stmt->execute([
      ':q'   => $quantity,
      ':pid' => $product_id
    ]);

    // Rediriger vers la page des ventes ou l'index
    header('Location: ../index.php?page=ventes');
    exit;
}
