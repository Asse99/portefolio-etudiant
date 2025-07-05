<header class="entete sticky-header">
    <div class="container-entete">
        <!--<input type="checkbox" id="themeToggle" class="theme-toggle">
        <label for="themeToggle" class="theme-label">Theme🌓</label>-->

        <!-- Bouton menu burger -->
        <button class="menu-toggle" id="menuToggle">☰</button>

        <h1><a href="index.php">✨ Mon Espace</a></h1>

        <!-- Menu principal -->
        <nav class="menu" id="navMenu">
            <ul>
                <li><a href="index.php">🏠 Accueil</a></li>
                <li><a href="a_propos.php">👤 À propos</a></li>
                <li><a href="projets.php">🧱 Projets récents</a></li>
                <li><a href="blog.php">📚 Blog</a></li>
                <li><a href="contact.php">✉️ Contact</a></li>
                <li><a href="auth/login.php">🔑 Connexion</a></li>
                <li><a href="auth/register.php">📝 S’inscrire</a></li>

            </ul>
        </nav>
    </div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                navMenu.classList.toggle('active');
            });

            document.addEventListener('click', (event) => {
                if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                    navMenu.classList.remove('active');
                }
            });

            const navLinks = navMenu.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                });
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById('themeToggle');
            const body = document.body;

            // Charger thème depuis le localStorage
            if (localStorage.getItem('theme') === 'dark') {
                toggle.checked = true;
                body.classList.add('dark-mode');
            }

            toggle.addEventListener('change', () => {
                if (toggle.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('theme', 'light');
                }
            });
        });
    </script>

</header>