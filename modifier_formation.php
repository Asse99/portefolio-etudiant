<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$erreur = '';
$succes = '';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = (int)$_GET['id'];

// Récupérer la formation à modifier
$stmt = $conn->prepare("SELECT * FROM formations WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
$formation = $result->fetch_assoc();

if (!$formation) {
    $erreur = "Formation introuvable.";
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $organisme = trim($_POST['organisme']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $description = trim($_POST['description']);

    if ($titre && $organisme) {
        $stmt = $conn->prepare("UPDATE formations SET titre = ?, organisme = ?, date_debut = ?, date_fin = ?, description = ? WHERE id = ? AND utilisateur_id = ?");
        $stmt->bind_param("ssssssi", $titre, $organisme, $date_debut, $date_fin, $description, $id, $id_utilisateur);
        if ($stmt->execute()) {
            header("Location: dashboard.php?succes=modif-formation");
            exit();
        } else {
            $erreur = "Erreur lors de la mise à jour.";
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
    <title>Modifier la formation</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <h2>✏️ Modifier la formation</h2>

    <?php if ($erreur): ?>
        <div class="message-erreur"><?= $erreur ?></div>
    <?php endif; ?>

    <?php if ($formation): ?>
        <form method="post">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($formation['titre']) ?>" required>

            <label for="organisme">Organisme</label>
            <input type="text" name="organisme" id="organisme" value="<?= htmlspecialchars($formation['organisme']) ?>" required>

            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" value="<?= $formation['date_debut'] ?>">

            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" value="<?= $formation['date_fin'] ?>">

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"><?= htmlspecialchars($formation['description']) ?></textarea>

            <button type="submit">💾 Enregistrer</button>
        </form>
    <?php endif; ?>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>
</body>

</html>