// admin.js
document.addEventListener('DOMContentLoaded', () => {
    // Exemple : si un message de succès est présent, on peut afficher un fade-out
    const successMsg = document.querySelector('p[style*="color: green"]');
    if (successMsg) {
      setTimeout(() => {
        successMsg.style.transition = 'opacity 0.8s';
        successMsg.style.opacity = 0;
      }, 3000);
    }
  });
  