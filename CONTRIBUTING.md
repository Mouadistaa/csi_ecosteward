echo "# 🤝 Guide de Contribution à EcoSteward 🌱

Bienvenue et merci pour votre intérêt à contribuer à **EcoSteward** !  
Ce document décrit les étapes et les bonnes pratiques pour contribuer efficacement à ce projet.

---

## 🚀 Comment Contribuer ?

### 1️⃣ **Forker et Cloner le projet**
Si vous souhaitez proposer des améliorations :

1. **Forkez** ce dépôt en cliquant sur le bouton \"Fork\" en haut à droite de la page GitHub.
2. **Clonez** votre fork sur votre machine :
   \`\`\`bash
   git clone https://github.com/votre-utilisateur/csi_ecosteward.git
   \`\`\`
3. **Accédez au dossier du projet** :
   \`\`\`bash
   cd csi_ecosteward
   \`\`\`

---

### 2️⃣ **Créer une Branche**
Avant de commencer à coder, créez une nouvelle branche basée sur \`main\` :

\`\`\`bash
git checkout -b feature/nom-fonction
\`\`\`

Par exemple :
\`\`\`bash
git checkout -b feature/gestion-utilisateurs
\`\`\`

---

### 3️⃣ **Coder et Tester**
- Développez votre fonctionnalité ou correction de bug.
- **Assurez-vous que le projet fonctionne** après vos modifications :
  \`\`\`bash
  npm install
  npm start
  \`\`\`
- **Si votre code modifie le comportement de l’application**, ajoutez des **tests unitaires** (\`npm test\`).

---

### 4️⃣ **Committer et Pusher les Modifications**
Une fois votre travail terminé :

1. **Ajoutez vos fichiers** :
   \`\`\`bash
   git add .
   \`\`\`
2. **Faites un commit avec un message clair** :
   \`\`\`bash
   git commit -m \"Ajout du module de gestion des utilisateurs\"
   \`\`\`
3. **Poussez votre branche sur GitHub** :
   \`\`\`bash
   git push origin feature/nom-fonction
   \`\`\`

---

### 5️⃣ **Créer une Pull Request**
Une fois votre code poussé :

1. **Allez sur GitHub** et ouvrez le dépôt de votre fork.
2. **Cliquez sur \"Compare & pull request\"**.
3. **Remplissez la description de votre Pull Request (PR)** :
   - Expliquez **ce que vous avez changé**.
   - Ajoutez **des captures d’écran** si nécessaire.
   - Indiquez si des tests ont été ajoutés/modifiés.

4. **Soumettez la PR et attendez les retours**.  
   L’équipe validera ou demandera des modifications.

---

## 📜 Règles de Contribution

Avant de soumettre une Pull Request, merci de respecter ces **règles** :

### ✅ **Style et Qualité du Code**
- Utilisez **ESLint et Prettier** pour un code propre :
  \`\`\`bash
  npm run lint
  npm run format
  \`\`\`
- Suivez le style du projet (**noms de variables, indentation, etc.**).
- Ajoutez des **commentaires** si votre code est complexe.

### ✅ **Tests et Validation**
- **Tous les tests doivent passer** avant de soumettre une PR :
  \`\`\`bash
  npm test
  \`\`\`
- **Ajoutez des tests** pour chaque nouvelle fonctionnalité.

### ✅ **Bonnes Pratiques Git**
- **Utilisez des commits clairs et lisibles**.  
  Exemples :
  \`\`\`bash
  git commit -m \"Fix: Correction du bug d'affichage des réservations\"
  git commit -m \"Feat: Ajout du module de gestion des cultures\"
  \`\`\`
- **Ne poussez pas de fichiers inutiles** (\`node_modules\`, \`.env\`, fichiers de logs…).
- **Ne modifiez pas directement \`main\`** → toujours créer une branche dédiée.

---

## 📩 Besoin d’Aide ?
Si vous avez une question ou un problème :
- **Ouvrez une Issue** sur GitHub.
- **Posez vos questions** dans les discussions du projet.

Merci pour votre contribution 💚 ! Ensemble, améliorons **EcoSteward** 🌱🚀" > CONTRIBUTING.md

# Ajoute le fichier à Git et le pousse sur GitHub
git add CONTRIBUTING.md
git commit -m "Ajout du guide de contribution"
git push
