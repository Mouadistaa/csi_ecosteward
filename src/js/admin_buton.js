document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne le lien
    const adminLink = document.querySelector('.admin-link');
  
    if (adminLink) {
      adminLink.addEventListener('click', (event) => {
        // Exemple: message de confirmation avant d'aller sur la page
        // Si vous mettez "false", l'utilisateur reste sur la page courante
        const shouldGo = confirm("Voulez-vous aller sur le Dashboard Admin ?");
        if (!shouldGo) {
          event.preventDefault(); // Annule la navigation
        }
      });
    }
  });
  