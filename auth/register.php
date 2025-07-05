<?php
include '../includes/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nom, $email, $mot_de_passe);
    $stmt->execute();

    header("Location: register.php?inscription=ok");
    exit();
}
if (isset($_GET['inscription']) && $_GET['inscription'] === 'ok') : ?>
    <div class="message-succes">✅ Inscription réussie ! Connecte-toi pour accéder à ton tableau de bord.</div>
<?php endif;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <button class="menu-toggle" id="menuToggle">☰</button>

    <h1><a href="../index.php">✨ Mon Espace</a></h1>

    <!-- Menu principal -->
    <nav class="menu" id="navMenu">
        <ul>
            <li><a href="../index.php">🏠 Accueil</a></li>
            <li><a href="../a_propos.php">👤 À propos</a></li>
            <li><a href="../projets.php">🧱 Projets récents</a></li>
            <li><a href="../blog.php">📚 Blog</a></li>
            <li><a href="../contact.php">✉️ Contact</a></li>
            <li><a href="login.php">🔑 Connexion</a></li>
            <li><a href="register.php">📝 S’inscrire</a></li>

        </ul>
    </nav>

    <div class="register-wrapper">
        <h2 class="log">Création de compte</h2>
        <form method="POST" action="register.php" class="formulaire-auth">
            <label for="nom">Nom complet :</label>
            <input type="text" name="nom" id="nom" required>

            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" id="email" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required>

            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
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

    <script src="../assets/js/scripts.js"></script>


</body>

</html>