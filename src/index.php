<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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

      <!-- MISE À JOUR : chaque data-section correspond à un ID -->
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
      <!-- ✅ AJOUTE LE BOUTON DÉCONNEXION ICI -->
      <div class="logout-container">
          <form action="pages/logout.php" method="POST">
              <button type="submit" class="logout-btn">🔓 Déconnexion</button>
          </form>
      </div>
    </nav>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main>
      <!-- ========== TABLEAU DE BORD ========== -->
      <section id="dashboard" aria-labelledby="dashboard-title">
        <h1 id="dashboard-title">Tableau de bord</h1>

        <!-- Bloc chiffres clés + mini chart -->
        <div class="grid-2">
          <div class="card">
            <h3>Chiffres Clés (Mensuel)</h3>
            <ul style="margin-top: .5rem; line-height: 1.6;">
              <li><strong>CA Total</strong>: 3 250 €</li>
              <li><strong>Ventes</strong>: 84 transactions</li>
              <li><strong>Ateliers</strong>: 5 sessions</li>
              <li><strong>Woofers actifs</strong>: 3</li>
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

        <!-- Autre bloc : Pie chart + progress ring -->
        <div class="grid-2">
          <div class="card">
            <h3>Répartition des ventes</h3>
            <p style="font-size:0.9em; color:#555;">(Produits / Catégories)</p>
            <div class="pie-placeholder" aria-hidden="true"></div>
            <ul style="margin-top: 0.5rem; font-size:0.9em;">
              <li><span style="color: var(--secondary-dark); font-weight:700;">■</span> Fromages (40%)</li>
              <li><span style="color: #ccc; font-weight:700;">■</span> Légumes (30%)</li>
              <li><span style="color: var(--primary); font-weight:700;">■</span> Oeufs & divers (30%)</li>
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

        <!-- Stock critique + ventes récentes -->
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
                <tr>
                  <td>Œufs Bio</td>
                  <td>120 unités</td>
                  <td>150 unités</td>
                </tr>
                <tr>
                  <td>Fromage</td>
                  <td>8 kg</td>
                  <td>10 kg</td>
                </tr>
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
                <tr>
                  <td>12/06 10:00</td>
                  <td>Œufs</td>
                  <td>2</td>
                  <td>3.00€</td>
                </tr>
                <tr>
                  <td>12/06 10:30</td>
                  <td>Fromage</td>
                  <td>1 kg</td>
                  <td>8.00€</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Woofers présents & Ateliers -->
        <div class="grid-2">
          <div class="card">
            <h3>Woofers présents</h3>
            <ul style="margin-top: .75rem;">
              <li>Marie (jusqu'au 25/06) – Missions : Vente, Soins animaux</li>
              <li>Pierre (jusqu'au 28/06) – Missions : Culture, Atelier</li>
              <li>Ali (jusqu'au 30/06) – Missions : Stocks, Accueil</li>
            </ul>
          </div>
          <div class="card">
            <h3>Prochains Ateliers</h3>
            <ul style="margin-top: .75rem;">
              <li><strong>Fabrication Fromage</strong> – 15/06 – 8/12 inscrits</li>
              <li><strong>Initiation Culture Bio</strong> – 20/06 – 4/8 inscrits</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ========== STOCKS ========== -->
      <section id="stock" aria-labelledby="stock-title">
        <h1 id="stock-title">📦 Gestion des stocks</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Mouvements de stock</h3>
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
                <tr>
                  <td>Œufs bio</td>
                  <td>120 unités</td>
                  <td>12/06 09:30</td>
                </tr>
                <!-- ...autres produits -->
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========== VENTES ========== -->
      <section id="ventes" aria-labelledby="ventes-title">
        <h1 id="ventes-title">💰 Vente rapide</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Nouvelle vente</h3>
            <div class="form-row">
              <label for="produitVente">Produit :</label>
              <select id="produitVente">
                <option>Œufs bio (1.50€/u)</option>
                <option>Fromage (8.00€/kg)</option>
              </select>
            </div>
            <div class="form-row">
              <label for="qteVente">Quantité :</label>
              <input type="number" id="qteVente" placeholder="Ex: 2" />
            </div>
            <div class="form-row">
              <label for="wooferResp">Woofer responsable :</label>
              <select id="wooferResp">
                <option>Marie D.</option>
                <option>Pierre M.</option>
              </select>
            </div>
            <button>Enregistrer</button>
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
                <tr>
                  <td>12/06 10:00</td>
                  <td>Œufs x2</td>
                  <td>3.00€</td>
                </tr>
                <!-- ... -->
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========== WOOFERS ========== -->
      <section id="woofers" aria-labelledby="woofers-title">
        <h1 id="woofers-title">👥 Gestion des Woofers</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Fiche information</h3>
            <div class="form-row">
              <label for="nomWoofer">Nom :</label>
              <input type="text" id="nomWoofer" placeholder="Marie Dupont" />
            </div>
            <div class="form-row" style="gap:10px; flex-direction: row;">
              <div style="flex:1;">
                <label for="dateDebut">Date début :</label>
                <input type="date" id="dateDebut" />
              </div>
              <div style="flex:1;">
                <label for="dateFin">Date fin :</label>
                <input type="date" id="dateFin" />
              </div>
            </div>
            <div class="form-row">
              <label for="competences">Compétences :</label>
              <textarea id="competences" rows="3">Soins animaux, Vente</textarea>
            </div>
            <button>Sauvegarder</button>
          </div>
          <div class="card">
            <h3>Planning hebdomadaire</h3>
            <div class="planning-grid">
              <div class="time-slot">08:00</div>
              <div class="task-list">
                <div class="task-item">
                  <span>Soins animaux</span>
                  <span class="task-category">Étable</span>
                </div>
              </div>
              <div class="time-slot">10:00</div>
              <div class="task-list">
                <div class="task-item" style="background: #4CAF50;">
                  <span>Vente produits</span>
                  <span class="task-category">Boutique</span>
                </div>
              </div>
              <div class="time-slot">14:00</div>
              <div class="task-list">
                <div class="task-item" style="background: #FF9800;">
                  <span>Atelier fromage</span>
                  <span class="task-category">Formation</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========== ATELIERS ========== -->
      <section id="ateliers" aria-labelledby="ateliers-title">
        <h1 id="ateliers-title">🎓 Ateliers</h1>
        <div class="grid-2">
          <div class="card">
            <h3>Nouvelle session</h3>
            <div class="form-row">
              <label for="nomAtelier">Nom de l'atelier :</label>
              <input type="text" id="nomAtelier" placeholder="Ex: Fabrication fromage" />
            </div>
            <div class="form-row">
              <label for="dateAtelier">Date :</label>
              <input type="date" id="dateAtelier" />
            </div>
            <div class="form-row">
              <label for="animateur">Animateur :</label>
              <select id="animateur">
                <option>Responsable: Marie</option>
                <option>Responsable: Pierre</option>
              </select>
            </div>
            <div class="form-row">
              <label for="placesMax">Places max :</label>
              <input type="number" id="placesMax" placeholder="12" />
            </div>
            <button>Créer</button>
          </div>
          <div class="card">
            <h3>Inscriptions (8/12)</h3>
            <table>
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Alice Martin</td>
                  <td>alice@mail.com</td>
                  <td>12/06</td>
                </tr>
                <tr>
                  <td>Jean Dupont</td>
                  <td>jean@exemple.com</td>
                  <td>(Liste d'attente)</td>
                </tr>
                <!-- ... -->
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