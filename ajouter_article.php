<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit;
}

$auteur_id = $_SESSION['utilisateur_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = $_POST['date_publication'] ?? '';

    $image_nom = '';
    if (!empty($_FILES['image']['name'])) {
        $image_nom = time() . '_' . basename($_FILES['image']['name']);
        $chemin_upload = 'uploads/' . $image_nom;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $chemin_upload)) {
            die("❌ Erreur upload image.");
        }
    }

    $stmt = $conn->prepare("INSERT INTO articles (titre, contenu, auteur_id, image, date_publication) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $titre, $contenu, $auteur_id, $image_nom, $date_publication);

    if ($stmt->execute()) {
        header("Location: gestion_articles.php?succes=article");
        exit;
    } else {
        echo "❌ Erreur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article - Espace Asse</title>
</head>

<body>
    <button class="menu-toggle" id="menuToggle">☰</button>

    <nav class="sidebar">
        <h1>👤 Mon Espace</h1>
        <ul>
            <li><a href="index.php">🏠 Accueil</a></li>
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            <li><a href="ajouter_formation.php">🧠 Ajouter une formation</a></li>
            <li><a href="ajouter_experience.php">🧳 Ajouter une expérience</a></li>
            <li><a href="ajouter_diplome.php">🎓 Ajouter un diplôme</a></li>
            <li><a href="ajouter_certification.php">📜 Ajouter une certification</a></li>
            <li><a href="ajouter_projet.php">📁 Ajouter un projet</a></li>
            <li><a href="gestion_articles.php">📝 Mes article</a></li>

        </ul>
    </nav>
    <main class="contenu">
        

        <h1>📝 Publier un nouvel article</h1>


        <form action="" method="POST" enctype="multipart/form-data">
            <label>Titre :</label><br>
            <input type="text" name="titre" required><br>

            <label>Contenu :</label><br>
            <textarea name="contenu" rows="5" cols="50" required></textarea><br>

            <label>Image (optionnelle) :</label><br>
            <input type="file" name="image"><br>

            <label>Date de publication :</label><br>
            <input type="date" name="date_publication" required><br><br>

            <input type="submit" value="📨 Publier l'article">
            <p><a href="gestion_articles.php">← Retour aux articles</a></p>

        </form>
    </main>
    <script src="assets/js/scripts.js"></script>

</body>

</html>