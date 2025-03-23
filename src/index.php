<?php
session_start();
require_once __DIR__ . '/includes/db_connect.php'; // Connexion PDO

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit;
}

/* -------------------------------------------------------------------------
   1) Requêtes SQL pour le tableau de bord, etc.
   ------------------------------------------------------------------------- */

// Nombre total de ventes
$stmt = $pdo->query("SELECT COUNT(*) FROM sales");
$nb_ventes = $stmt->fetchColumn();

// Chiffre d'affaires total
$stmt = $pdo->query("SELECT SUM(quantity * prix_unitaire) FROM sales");
$ca_total = $stmt->fetchColumn() ?? 0;

// Nombre d'ateliers
$stmt = $pdo->query("SELECT COUNT(*) FROM workshops");
$nb_ateliers = $stmt->fetchColumn();

// Nombre de woofers actifs
$stmt = $pdo->query("SELECT COUNT(*) FROM woofers WHERE end_date >= CURDATE()");
$nb_woofers = $stmt->fetchColumn();

// Produits par catégorie
$stmt = $pdo->query("
  SELECT category, COUNT(*) AS nb
  FROM products
  GROUP BY category
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stock critique
$stmt = $pdo->query("
  SELECT name, stock, seuil_alerte
  FROM products
  WHERE stock < seuil_alerte
");
$stocks_critiques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ventes récentes
$stmt = $pdo->query("
  SELECT s.sale_date, p.name, s.quantity, s.prix_unitaire
  FROM sales s
  JOIN products p ON s.product_id = p.id
  ORDER BY s.sale_date DESC
  LIMIT 5
");
$ventes_recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
$last_sales = $ventes_recent; // alias

// Woofers actifs
$stmt = $pdo->query("
  SELECT name, end_date, competencies
  FROM woofers
  WHERE end_date >= CURDATE()
");
$woofers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prochains ateliers (on sélectionne aussi w.id pour l'utiliser ensuite)
$stmt = $pdo->query("
  SELECT w.id,
         w.title,
         w.workshop_date,
         (SELECT COUNT(*) FROM registrations r WHERE r.workshop_id = w.id) AS nb_inscrits,
         w.capacity
  FROM workshops w
  WHERE w.workshop_date >= CURDATE()
  ORDER BY w.workshop_date ASC
  LIMIT 5
");
$ateliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------------------------------------------------------------
   2) Préparer des listes pour remplir les <select> (Nouvelle vente, etc.)
   ------------------------------------------------------------------------- */

// Liste de TOUS les produits (pour la “Nouvelle vente”)
$stmt = $pdo->query("SELECT id, name, price, stock FROM products ORDER BY name");
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste de TOUS les users (admin ou woofer) pour la vente
$stmt = $pdo->query("SELECT id, email, role FROM users ORDER BY email");
$allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste de TOUS les woofers (pour “Animateur” d’atelier)
$stmt = $pdo->query("SELECT id, name FROM woofers ORDER BY name");
$animators = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------------------------------------------------------------
   3) Planning dynamique
   ------------------------------------------------------------------------- */
$stmt = $pdo->query("
  SELECT p.id,
         p.plan_date,
         p.start_time,
         p.end_time,
         p.task_name,
         p.location,
         w.name AS woofer_name
  FROM planning p
  LEFT JOIN woofers w ON p.woofer_id = w.id
  ORDER BY p.plan_date, p.start_time
");
$planningItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------------------------------------------------------------
   4) Récupérer les inscriptions du *premier* atelier à venir (s'il existe)
   ------------------------------------------------------------------------- */
$inscriptions = [];
if (!empty($ateliers)) {
    // On prend l'ID du premier atelier dans la liste
    $firstWorkshopId = $ateliers[0]['id'];

    // Sélection de toutes les inscriptions associées à cet atelier
    $stmt = $pdo->prepare("
      SELECT participant_name, participant_email, registered_at
      FROM registrations
      WHERE workshop_id = ?
      ORDER BY registered_at ASC
    ");
    $stmt->execute([$firstWorkshopId]);
    $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>EcoSteward – Gestion de ferme écoresponsable</title>

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
  <!-- Ton fichier CSS principal -->
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <div class="app-container">
    <!-- ===================== SIDEBAR ===================== -->
    <nav class="sidebar" aria-label="Menu principal">
      <div class="logo-container">
        <div class="logo-icon" aria-hidden="true">
          <div class="logo-shield"></div>
          <div class="logo-leaf"></div>
        </div>
        <div class="logo-text">Eco<span>Steward</span></div>
      </div>

      <div class="nav-item active" data-section="dashboard" tabindex="0">
        📊 Tableau de bord
      </div>
      <div class="nav-item" data-section="stock" tabindex="0">
        📦 Gestion stock
      </div>
      <div class="nav-item" data-section="ventes" tabindex="0">
        💰 Ventes rapides
      </div>
      <div class="nav-item" data-section="woofers" tabindex="0">
        👥 Woofers
      </div>
      <div class="nav-item" data-section="ateliers" tabindex="0">
        🎓 Ateliers
      </div>

      <!-- Bouton de Déconnexion -->
      <div class="logout-container">
        <form action="pages/logout.php" method="POST">
          <button type="submit" class="logout-btn">🔓 Déconnexion</button>
        </form>
      </div>
    </nav>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main>
      <!-- ========== 1) TABLEAU DE BORD ========== -->
      <section id="dashboard" aria-labelledby="dashboard-title">
        <h1 id="dashboard-title">Tableau de bord</h1>

        <!-- Bloc chiffres clés + mini chart -->
        <div class="grid-2">
          <div class="card">
            <h3>Chiffres Clés (Mensuel)</h3>
            <ul style="margin-top: .5rem; line-height: 1.6;">
              <li><strong>CA Total</strong> :
                  <?= number_format($ca_total, 2, ',', ' ') ?> €</li>
              <li><strong>Ventes</strong> :
                  <?= $nb_ventes ?> transactions</li>
              <li><strong>Ateliers</strong> :
                  <?= $nb_ateliers ?> sessions</li>
              <li><strong>Woofers actifs</strong> :
                  <?= $nb_woofers ?></li>
            </ul>
          </div>
          <div class="card">
            <h3>Tendance des ventes (7 jours)</h3>
            <p style="font-size: 0.9em; color: #666; margin-top: 0.3rem;">
              (Placeholder dégradé)
            </p>
            <div class="mini-chart" aria-hidden="true"></div>
          </div>
        </div>

        <!-- Répartition produits + Capacité Ateliers -->
        <div class="grid-2">
          <div class="card">
            <h3>Répartition des produits</h3>
            <p style="font-size:0.9em; color:#555;">(par catégorie)</p>
            <div class="pie-placeholder" aria-hidden="true"></div>
            <ul style="margin-top: 0.5rem; font-size:0.9em;">
              <?php foreach ($categories as $cat): ?>
                <li>
                  <span style="color: var(--primary); font-weight:700;">■</span>
                  <?= htmlspecialchars($cat['category']) ?> (<?= $cat['nb'] ?> produits)
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="card">
            <h3>Capacité Ateliers (Moyenne)</h3>
            <p style="font-size:0.9em; color:#555;">En cours / total places</p>
            <div class="progress-ring" aria-hidden="true">
              <div class="progress-center">75%</div>
            </div>
          </div>
        </div>

        <!-- Stock critique + Ventes récentes -->
        <div class="grid-2">
          <div class="card">
            <h3>Stock critique</h3>
            <table>
              <thead>
                <tr>
                  <th>Produit</th>
                  <th>Stock</th>
                  <th>Seuil</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($stocks_critiques as $p): ?>
                  <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td><?= $p['seuil_alerte'] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="card">
            <h3>Ventes récentes</h3>
            <table>
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Produit</th>
                  <th>Quantité</th>
                  <th>Montant</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ventes_recent as $v): ?>
                  <tr>
                    <td><?= date('d/m H:i', strtotime($v['sale_date'])) ?></td>
                    <td><?= htmlspecialchars($v['name']) ?></td>
                    <td><?= $v['quantity'] ?></td>
                    <td><?= number_format($v['quantity'] * $v['prix_unitaire'], 2, ',', ' ') ?> €</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Woofers présents + Prochains Ateliers -->
        <div class="grid-2">
          <div class="card">
            <h3>Woofers présents</h3>
            <ul style="margin-top: .75rem;">
              <?php foreach ($woofers as $w): ?>
                <li>
                  <?= htmlspecialchars($w['name']) ?>
                  (jusqu'au <?= date('d/m', strtotime($w['end_date'])) ?>)
                  – Missions : <?= htmlspecialchars($w['competencies']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="card">
            <h3>Prochains Ateliers</h3>
            <ul style="margin-top: .75rem;">
              <?php foreach ($ateliers as $a): ?>
                <li>
                  <strong><?= htmlspecialchars($a['title']) ?></strong>
                  – <?= date('d/m', strtotime($a['workshop_date'])) ?>
                  – <?= $a['nb_inscrits'] ?>/<?= $a['capacity'] ?> inscrits
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>

      <!-- ========== 2) STOCKS ========== -->
      <section id="stock" aria-labelledby="stock-title">
        <h1 id="stock-title">📦 Gestion des stocks</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Mouvements de stock (exemple)</h3>
            <!-- Juste un formulaire illustratif (non relié à un "add_stock.php") -->
            <div class="form-row">
              <label for="typeMouvement">Type de mouvement :</label>
              <select id="typeMouvement">
                <option>Entrée stock</option>
                <option>Sortie stock</option>
              </select>
            </div>
            <div class="form-row">
              <label for="dateMouv">Date :</label>
              <input type="date" id="dateMouv" />
            </div>
            <div class="form-row">
              <label for="qteMouv">Quantité :</label>
              <input type="number" id="qteMouv" placeholder="Ex: 10" />
            </div>
            <button>Valider</button>
          </div>

          <div class="card">
            <h3>État des stocks</h3>
            <table>
              <thead>
                <tr>
                  <th>Produit</th>
                  <th>Stock</th>
                  <th>Dernière mise à jour</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // On recharge tous les produits
                  $stmt = $pdo->query("SELECT * FROM products ORDER BY name");
                  $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($all_products as $prod) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($prod['name']) . "</td>";
                    echo "<td>" . $prod['stock'] . "</td>";
                    echo "<td>" . date('d/m/Y H:i', strtotime($prod['created_at'])) . "</td>";
                    echo "</tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========== 3) VENTES ========== -->
      <section id="ventes" aria-labelledby="ventes-title">
        <h1 id="ventes-title">💰 Vente rapide</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Nouvelle vente</h3>
            <form action="actions/add_sale.php" method="POST">
              <div class="form-row">
                <label for="user_id">Vendu par :</label>
                <select id="user_id" name="user_id">
                  <?php foreach($allUsers as $u): ?>
                    <option value="<?= $u['id'] ?>">
                      <?= htmlspecialchars($u['email']) ?> (<?= $u['role'] ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-row">
                <label for="product_id">Produit :</label>
                <select id="product_id" name="product_id">
                  <?php foreach($allProducts as $p): ?>
                    <option value="<?= $p['id'] ?>">
                      <?= htmlspecialchars($p['name']) ?> (<?= $p['price'] ?> €/u)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-row">
                <label for="quantity">Quantité :</label>
                <input type="number" id="quantity" name="quantity" placeholder="Ex: 2" required />
              </div>
              <div class="form-row">
                <label for="prix_unitaire">Prix unitaire :</label>
                <input type="number" step="0.01" id="prix_unitaire" name="prix_unitaire" placeholder="Ex: 1.50" />
              </div>
              <button type="submit">Enregistrer</button>
            </form>
          </div>
          <div class="card">
            <h3>Dernières ventes</h3>
            <table>
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Produit</th>
                  <th>Montant</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($last_sales as $sale): ?>
                  <tr>
                    <td><?= date('d/m H:i', strtotime($sale['sale_date'])) ?></td>
                    <td><?= htmlspecialchars($sale['name']) ?> x <?= $sale['quantity'] ?></td>
                    <td><?= number_format($sale['quantity'] * $sale['prix_unitaire'], 2, ',', ' ') ?> €</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========== 4) WOOFERS ========== -->
      <section id="woofers" aria-labelledby="woofers-title">
        <h1 id="woofers-title">👥 Gestion des Woofers</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Fiche information (exemple)</h3>
            <!-- Pour un ajout en base : <form action="actions/add_woofer.php" method="POST"> -->
            <div class="form-row">
              <label for="nomWoofer">Nom :</label>
              <input type="text" id="nomWoofer" name="nomWoofer" placeholder="Marie Dupont" />
            </div>
            <div class="form-row" style="gap:10px; flex-direction: row;">
              <div style="flex:1;">
                <label for="dateDebut">Date début :</label>
                <input type="date" id="dateDebut" name="dateDebut" />
              </div>
              <div style="flex:1;">
                <label for="dateFin">Date fin :</label>
                <input type="date" id="dateFin" name="dateFin" />
              </div>
            </div>
            <div class="form-row">
              <label for="competences">Compétences :</label>
              <textarea id="competences" name="competences" rows="3">Soins animaux, Vente</textarea>
            </div>
            <button>Sauvegarder</button>
          </div>

          <!-- Planning (dynamique depuis la table "planning") -->
          <div class="card">
            <h3>Planning (issu de la DB)</h3>
            <table>
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Horaire</th>
                  <th>Tâche</th>
                  <th>Woofer</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($planningItems as $p): ?>
                  <tr>
                    <td><?= date('d/m/Y', strtotime($p['plan_date'])) ?></td>
                    <td>
                      <?= substr($p['start_time'], 0, 5) ?>
                      -
                      <?= substr($p['end_time'], 0, 5) ?>
                    </td>
                    <td>
                      <?= htmlspecialchars($p['task_name']) ?>
                      (<?= htmlspecialchars($p['location']) ?>)
                    </td>
                    <td><?= htmlspecialchars($p['woofer_name'] ?? '') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========== 5) ATELIERS ========== -->
      <section id="ateliers" aria-labelledby="ateliers-title">
        <h1 id="ateliers-title">🎓 Ateliers</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Nouvelle session</h3>
            <form action="actions/add_workshop.php" method="POST">
              <div class="form-row">
                <label for="title">Nom de l'atelier :</label>
                <input type="text" id="title" name="title" placeholder="Ex: Fabrication fromage" required />
              </div>
              <div class="form-row">
                <label for="workshop_date">Date :</label>
                <input type="date" id="workshop_date" name="workshop_date" required />
              </div>
              <div class="form-row">
                <label for="animator_id">Animateur :</label>
                <select id="animator_id" name="animator_id">
                  <?php foreach($animators as $an): ?>
                    <option value="<?= $an['id'] ?>">
                      <?= htmlspecialchars($an['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-row">
                <label for="capacity">Places max :</label>
                <input type="number" id="capacity" name="capacity" placeholder="12" />
              </div>
              <button type="submit">Créer</button>
            </form>
          </div>

          <div class="card">
            <h3>Inscriptions du premier atelier à venir</h3>
            <table>
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($inscriptions)): ?>
                  <?php foreach ($inscriptions as $i): ?>
                    <tr>
                      <td><?= htmlspecialchars($i['participant_name']) ?></td>
                      <td><?= htmlspecialchars($i['participant_email']) ?></td>
                      <td><?= date('d/m', strtotime($i['registered_at'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" style="text-align:center; font-style:italic;">
                      Aucune inscription pour cet atelier (ou aucun atelier à venir).
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- ===================== JS : NAVIGATION ===================== -->
  <script src="js/app.js"></script>
</body>
</html>
