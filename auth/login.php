<?php
include '../includes/db.php';

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $utilisateur = $result->fetch_assoc();

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['nom'];
        header("Location: ../dashboard.php");
    } else {
        header("Location: login.php?erreur=connexion");
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="conte">

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
    </div>

    <div class="login-wrapper">
        <marquee behavior="alternate" direction="">
            <h2>Bienvenue, Sur My Portfolio 👋</h2>
        </marquee>
        <?php if (isset($_GET['succes']) && $_GET['succes'] === 'inscription'): ?>
            <div class="message-succes">✅ Inscription réussie ! Tu peux maintenant te connecter.</div>
        <?php endif; ?>

        <?php if (isset($_GET['erreur']) && $_GET['erreur'] === 'connexion'): ?>
            <div class="message-erreur">❌ Email ou mot de passe incorrect.</div>
        <?php endif; ?>
        <div class="container">
            <h2 class="log">Connexion</h2>
            <form method="POST" action="login.php" class="formulaire-auth">
                <label for="email">Adresse e-mail :</label>
                <input type="email" name="email" id="email" autocomplete="email" required>

                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required>

                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
        </div>
    </div>

    <script src="../assets/js/scripts.js"></script>
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

</body>

</html>
<?php
// Fermer la connexion à la base de données 
$conn->close();
?>
<?php
// Fin du fichier login.php