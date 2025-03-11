# 📢 Pull Request

## 📌 Description
Merci de décrire brièvement votre contribution.

## 🛠️ Changements effectués
- [ ] Correction d'un bug 🐛
- [ ] Nouvelle fonctionnalité 🚀
- [ ] Mise à jour de la documentation 📚

## ✅ Tests effectués
- [ ] Tests unitaires ajoutés
- [ ] Fonctionnalité testée localement

## 🔗 Liens et Screenshots (si applicable)
Ajoutez ici des captures d’écran ou des liens vers des tests.
"@

# Étape 4 : Créer le fichier Template pour le signalement de bugs
New-Item .github/ISSUE_TEMPLATE/bug_report.md -ItemType File

# Étape 5 : Ajouter le contenu du template de bug report
Set-Content .github/ISSUE_TEMPLATE/bug_report.md @"
---
name: "🐛 Bug Report"
about: "Signaler un bug"
title: "[BUG] - Décrivez le problème ici"
labels: bug
assignees: ''

---

## 📌 Description du problème
Décrivez le bug de manière claire.

## 🛠️ Étapes pour reproduire
1. Aller à '...'
2. Cliquer sur '...'
3. Voir l'erreur '...'

## 📷 Captures d’écran (si possible)
Ajoutez des captures d’écran du bug.

## 💻 Environnement
- **OS** : (Windows/Linux/Mac)
- **Navigateur/Version Node.js** :