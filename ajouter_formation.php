<?php
session_start();
require_once 'includes/db.php';

// Sécurité : vérifier que l’utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $organisme = trim($_POST['organisme']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $description = trim($_POST['description']);

    if ($titre && $organisme) {
        $stmt = $conn->prepare("INSERT INTO formations (utilisateur_id, titre, organisme, date_debut, date_fin, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $id_utilisateur, $titre, $organisme, $date_debut, $date_fin, $description);
        if ($stmt->execute()) {
            header("Location: dashboard.php?succes=formation");
            exit();
        } else {
            $erreur = "Erreur lors de l'ajout.";
        }
    } else {
        $erreur = "Le titre et l’organisme sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une formation</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
   
        <h2>🧠 Ajouter une formation</h2>

        <?php if ($succes): ?>
            <div class="message-succes"><?= $succes ?></div>
        <?php elseif ($erreur): ?>
            <div class="message-erreur"><?= $erreur ?></div>
        <?php endif; ?>

        <form method="post" class="formulaire-ajout">
            <label for="titre">Titre de la formation</label>
            <input type="text" name="titre" id="titre" required>

            <label for="organisme">Organisme</label>
            <input type="text" name="organisme" id="organisme" required>

            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut">

            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin">

            <label for="description">Description (facultatif)</label>
            <textarea name="description" id="description" rows="4"></textarea>

            <button type="submit">➕ Ajouter la formation</button>
        </form>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

</body>

</html>