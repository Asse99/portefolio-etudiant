<?php
require 'includes/db.php'; // Tu utilises $conn ici
include 'includes/header.php'; // Inclure l'en-tête si nécessaire
$result = $conn->query("SELECT * FROM articles ORDER BY date_publication DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog d’Asse 📝</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1 style="text-align:center">📚 Derniers articles</h1>

    <div class="articles">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($article = $result->fetch_assoc()): ?>
                <div class="carte-article">
                    
                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                    <p><em><?= htmlspecialchars($article['auteur']) ?> — <?= date('d/m/Y', strtotime($article['date_publication'])) ?></em></p>
                    <p><?= mb_substr(strip_tags($article['contenu']), 0, 160) ?>...</p>
                    <a href="article.php?id=<?= $article['id'] ?>">Lire la suite ➔</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun article publié pour le moment.</p>
        <?php endif; ?>
    </div>

</body>

</html>