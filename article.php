<?php
require 'includes/db.php';
include 'includes/header.php'; // Inclure l'en-tête si nécessaire

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = (int) $_POST['article_id'];
    $nom = htmlspecialchars(trim($_POST['nom']));
    $contenu = htmlspecialchars(trim($_POST['contenu']));

    if ($nom && $contenu) {
        $stmtCom = $conn->prepare("INSERT INTO commentaires (article_id, nom, contenu) VALUES (?, ?, ?)");
        $stmtCom->bind_param("iss", $article_id, $nom, $contenu);
        $stmtCom->execute();
    }
}
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    die("⛔ ID article invalide.");
}

// Récupérer l'article
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    die("❌ Article introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?></title>
</head>

<body>

    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p><em>Par <?= htmlspecialchars($article['auteur']) ?> — le <?= date('d/m/Y', strtotime($article['date_publication'])) ?></em></p>

    <?php if ($article['image']): ?>
        <img src="uploads/<?= htmlspecialchars($article['image']) ?>" alt="Image" style="max-width:400px;">
    <?php endif; ?>

    <div>
        <?= nl2br(htmlspecialchars($article['contenu'])) ?>
    </div>

    <hr>

    <h3>💬 Commentaires</h3>
    <?php
    $coms = $conn->prepare("SELECT nom, contenu, date_commentaire FROM commentaires WHERE article_id = ? ORDER BY date_commentaire DESC");
    $coms->bind_param("i", $id);
    $coms->execute();
    $result = $coms->get_result();

    if ($result->num_rows > 0):
        while ($c = $result->fetch_assoc()): ?>
            <div style="margin-bottom:10px;">
                <strong><?= htmlspecialchars($c['nom']) ?></strong> le <?= date('d/m/Y à H:i', strtotime($c['date_commentaire'])) ?><br>
                <?= nl2br(htmlspecialchars($c['contenu'])) ?>
            </div>
        <?php endwhile;
    else: ?>
        <p>Aucun commentaire pour cet article. Soyez le premier !</p>
    <?php endif; ?>

    <hr>

    <h3>📝 Laisser un commentaire</h3>
    <form action="" method="POST">
        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
        <label>Nom :</label><br>
        <input type="text" name="nom" required><br><br>
        <label>Commentaire :</label><br>
        <textarea name="contenu" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="📨 Envoyer">
    </form>



</body>

</html>