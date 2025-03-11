document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('main > section');
  
    function showSection(sectionId) {
      sections.forEach(sec => sec.style.display = 'none');
      const target = document.getElementById(sectionId);
      if (target) target.style.display = 'block';
    }
  
    navItems.forEach(item => {
      item.addEventListener('click', () => {
        navItems.forEach(n => n.classList.remove('active'));
        item.classList.add('active');
        showSection(item.dataset.section);
      });
  
      // Accessibilité clavier : permet d'activer une section avec la touche "Entrée"
      item.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          navItems.forEach(n => n.classList.remove('active'));
          item.classList.add('active');
          showSection(item.dataset.section);
        }
      });
    });
  
    // Afficher par défaut la section "Tableau de bord"
    showSection('dashboard');
  });
  