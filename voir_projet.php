<?php
require 'includes/db.php';
include 'includes/header.php';


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    die("⛔ ID de projet invalide.");
}

// Récupérer les détails du projet
$stmt = $conn->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$projet = $stmt->get_result()->fetch_assoc();

if (!$projet) {
    die("❌ Ce projet n'existe pas.");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($projet['titre']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
            width: 80%;
            margin: 40px auto;
        }

        img {
            max-width: 100%;
            margin-top: 15px;
            border-radius: 6px;
        }

        .technos {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #007BFF;
        }

        .retour {
            margin-top: 30px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1><?= htmlspecialchars($projet['titre']) ?></h1>

        <p><strong>📅 Date :</strong> <?= htmlspecialchars($projet['date_projet']) ?></p>

        <?php if (!empty($projet['image'])): ?>
            <img src="<?= htmlspecialchars($projet['image']) ?>" alt="Image du projet">
        <?php endif; ?>

        <?php if (!empty($projet['technologies'])): ?>
            <div class="technos">
                <strong>🛠️ Technologies utilisées :</strong><br>
                <?= nl2br(htmlspecialchars($projet['technologies'])) ?>
            </div>
        <?php endif; ?>

        <h2²>📝 Description</h2>
        <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>

        <?php if (!empty($projet['lien'])): ?>
            <p><a href="<?= htmlspecialchars($projet['lien']) ?>" target="_blank">🔗 Lien externe du projet</a></p>
        <?php endif; ?>

        <a class="retour" href="projets.php">← Retour aux projets</a>
    </div>

</body>

</html>