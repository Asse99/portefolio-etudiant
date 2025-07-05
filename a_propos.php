<?php
session_start();
$titrePage = "À propos - Espace Asse";
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titrePage; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <section class="section-a-propos">
            <h2>👋 À propos de moi</h2>
            <img src="assets/img/photo.jpg" alt="Photo Asse" class="photo-profil">

            <p>
                Je suis <strong>Asse Guèdè Ndir</strong>, passionné de <strong>développement web</strong>, curieux en <strong>cybersécurité</strong> 🔐, et pratiquant de <strong>taekwondo</strong> 🥋.
            </p>

            <p>
                🎓 Actuellement étudiant en génie logiciel à <em>Hemi</em>, je m’épanouis dans les projets qui combinent <strong>résilience numérique</strong>, <strong>interface élégante</strong> et <strong>fonctionnalité dynamique</strong>.
            </p>

            <div class="timeline">
                <div class="etape">
                    <h4>🎓 2022 — Présent</h4>
                    <p>Étudiant en génie logiciel à HEMI</p>
                </div>
                <div class="etape">
                    <h4>💻 2023 — Aujourd’hui</h4>
                    <p>Projets dynamiques en PHP, SQL, responsive CSS</p>
                </div>
                <div class="etape">
                    <h4>🥋 Depuis toujours</h4>
                    <p>Engagé dans la communauté taekwondo, esprit d’équipe et discipline</p>
                </div>
            </div>


            <p>
                💼 Sur ce site, tu découvriras un aperçu de mes compétences, mes expériences professionnelles, mes formations, certifications et projets récents.
            </p>

            <p>
                ☀️ Mon objectif : bâtir des solutions utiles, performantes et accessibles à tous.
            </p>
            <blockquote class="citation">
                “La persévérance transforme le potentiel en pouvoir.”
            </blockquote>

        </section>
    </main>


</body>