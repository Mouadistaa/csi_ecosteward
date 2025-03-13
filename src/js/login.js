// Script JS pour animer ou valider le formulaire de login
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
  
    // Exemple de validation JS basique avant envoi
    loginForm.addEventListener('submit', (e) => {
      const emailField = document.getElementById('email');
      const passwordField = document.getElementById('password');
  
      if (!emailField.value.includes('@')) {
        alert("Veuillez saisir un email valide.");
        e.preventDefault(); // Empêche la soumission
      }
  
      if (passwordField.value.length < 4) {
        alert("Mot de passe trop court !");
        e.preventDefault();
      }
    });
  });
  