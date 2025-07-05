<?php
require 'includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    die("⛔ ID article non valide.");
}

$result = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$result->bind_param("i", $id);
$result->execute();
$article = $result->get_result()->fetch_assoc();

if (!$article) {
    die("❌ Article introuvable.");
}
?>
<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = $_POST['date_publication'] ?? '';
    $image_nom = '';

    // Si une nouvelle image est uploadée
    if (!empty($_FILES['image']['name'])) {
        $image_nom = time() . '_' . basename($_FILES['image']['name']);
        $chemin_upload = 'uploads/' . $image_nom;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $chemin_upload)) {
            die("❌ Erreur lors du chargement de l'image.");
        }

        // Mise à jour avec image
        $req = $conn->prepare("UPDATE articles SET titre=?, contenu=?, date_publication=?, image=? WHERE id=?");
        $req->bind_param("ssssi", $titre, $contenu, $date_publication, $image_nom, $id);
    } else {
        // Mise à jour sans changer l’image
        $req = $conn->prepare("UPDATE articles SET titre=?, contenu=?, date_publication=? WHERE id=?");
        $req->bind_param("sssi", $titre, $contenu, $date_publication, $id);
    }

    if ($req->execute()) {
        header("Location: gestion_articles.php?succes=modif-article");
        exit;
    } else {
        echo "❌ Erreur de mise à jour : " . $req->error;
    }

    $req->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Modifier l'article</title>
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
        <h1>✏️ Modifier l’article</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $article['id'] ?>">

            <label>Titre :</label><br>
            <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required><br><br>

            <label>Contenu :</label><br>
            <textarea name="contenu" rows="5" cols="60" required><?= htmlspecialchars($article['contenu']) ?></textarea><br><br>

            <label>Date de publication :</label><br>
            <input type="date" name="date_publication" value="<?= $article['date_publication'] ?>" required><br><br>

            <?php if ($article['image']): ?>
                <img src="uploads/<?= htmlspecialchars($article['image']) ?>" width="120"><br>
            <?php else: ?>
            <?php endif; ?>
            <label>Charger l’image :</label>
            <input type="file" name="image"><br><br>

            <input type="submit" value="💾 Enregistrer les modifications">
            <p><a href="gestion_articles.php">← Retour aux articles</a></p>

        </form>
    </main>
        <script src="assets/js/scripts.js"></script>
</body>

</html>