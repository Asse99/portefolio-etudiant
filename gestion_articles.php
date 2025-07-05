<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit;
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$message_succes = $_SESSION['message_succes'] ?? '';
unset($_SESSION['message_succes']);

$stmt = $conn->prepare("SELECT * FROM articles WHERE auteur_id = ? ORDER BY date_publication DESC");
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();


// Messages de succès/erreur
$messagesSucces = [
    'article' => '🎓 article ajouté avec succès !',
    'suppr-article' => '🗑️ article supprimée !',
    'modif-article' => '✏️ article modifié avec succès !',

];
$messagesErreur = [
    'article' => '❌ Erreur lors de l\'ajout de l\'article.',
    'modif-article' => '❌ Erreur lors de la modification de l\'article.',
    'suppr-article' => '❌ Échec de la suppression de l\'article. Essaie à nouveau.',
];
$succes = $_GET['succes'] ?? null;
$erreur = $_GET['erreur'] ?? null;
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion de mes articles</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: #fff;
            border-radius: 4px;
        }



        .add {
            background-color: #007bff;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <button class="menu-toggle" id="menuToggle">☰</button>

    <nav class="sidebar">
        <h1>👤 Mon Espace</h1>
        <ul>
            <li><a href="index.php">🏠 Accueil</a></li>
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            <li><a href="ajouter_article.php" class="add">+ Nouvel article</a></li>
        </ul>
    </nav>



    <main class="contenu">
        <!-- Messages -->
        <?php if (isset($messagesSucces[$succes])): ?>
            <div class="message-succes"><?= $messagesSucces[$succes] ?></div>
        <?php endif; ?>
        <?php if (isset($messagesErreur[$erreur])): ?>
            <div class="message-erreur"><?= $messagesErreur[$erreur] ?></div>
        <?php endif; ?>
        <h1>📝 Mes articles publiés</h1>

        <?php if ($result->num_rows === 0): ?>
            <p style="text-align:center;">📭 Aucun article publié par vous pour le moment.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($a = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['titre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($a['date_publication'])) ?></td>
                        <td>
                            <a class="btn edit" href="modifier_article.php?id=<?= $a['id'] ?>">Modifier</a>
                            <a class="btn delete" href="supprimer_article.php?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet article ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </main>

    <script src="assets/js/scripts.js"></script>
</body>

</html>