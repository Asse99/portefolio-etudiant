// Masquer automatiquement les messages de succès après 5 secondes
document.addEventListener("DOMContentLoaded", () => {
    const message = document.querySelector('.message-succes');
    if (message) {
        setTimeout(() => {
            message.style.transition = 'opacity 0.6s ease';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 600);
        }, 5000);
    }
});


document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById('menuToggle');
  const sidebar = document.querySelector('.sidebar');

  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      sidebar.classList.toggle('active');
    });

    // Fermer au clic à l’extérieur
    document.addEventListener('click', (event) => {
      if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });

    // Fermer au clic sur lien
    const links = sidebar.querySelectorAll('a');
    links.forEach(link => {
      link.addEventListener('click', () => {
        sidebar.classList.remove('active');
      });
    });
  }
});

// recaptcha

  function onClick(e) {
    e.preventDefault();
    grecaptcha.enterprise.ready(async () => {
      const token = await grecaptcha.enterprise.execute('6LfgNnErAAAAAGbvKkyi8xQlgrsvfw2CwmATLrka', {action: 'LOGIN'});
    });
  }


