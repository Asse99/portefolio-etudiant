<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';


// 🔐 Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

// 🔎 Récupérer l'ID de l'administrateur
$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE role = 'admin' LIMIT 1");
$stmt->execute();
$resAdmin = $stmt->get_result();
$admin = $resAdmin->fetch_assoc();


$admin_id = $admin['id'];

// 📦 Récupérer les projets de l’admin
$stmt = $conn->prepare("SELECT * FROM projets WHERE utilisateur_id = ? ORDER BY date_projet DESC");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$projets = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Projets de l’administrateur</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .projets-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px;
        }

        .carte-projet {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 6px;
            width: 400px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .carte-projet h3 {
            margin-top: 0;
        }

        .carte-projet img {
            max-width: 100%;
            border-radius: 4px;
        }
    </style>
</head>

<body>


    <h1>💼 Projets récents de l’admin</h1>

    <?php if ($projets->num_rows === 0): ?>
        <p>📭 Aucun projet disponible pour le moment.</p>
    <?php else: ?>
        <div class="projets-container">
            <?php while ($p = $projets->fetch_assoc()): ?>
                <div class="carte-projet">
                    <h3>
                        <a href="projets.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['titre']) ?></a>
                    </h3>


                    <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" alt="Image du projet">
                    <?php endif; ?>

                    <p><strong>Date :</strong> <?= htmlspecialchars($p['date_projet']) ?></p>

                    <?php if (!empty($p['technologies'])): ?>
                        <p><strong>Technologies :</strong><br>
                            <?= nl2br(htmlspecialchars($p['technologies'])) ?>
                        </p>
                    <?php endif; ?>

                    <p><?= nl2br(htmlspecialchars(mb_strimwidth($p['description'], 0, 120, '...'))) ?></p>

                    <a href="voir_projet.php?id=<?= $p['id'] ?>">🧭 Voir les détails</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <p style="text-align:center;"><a href="dashboard.php">← Retour au tableau de bord</a></p>


</body>

</html>