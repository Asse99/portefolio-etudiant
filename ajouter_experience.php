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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poste = trim($_POST['poste']);
    $entreprise = trim($_POST['entreprise']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($poste && $entreprise) {
        $stmt = $conn->prepare("INSERT INTO experiences (utilisateur_id, poste, entreprise, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_utilisateur, $poste, $entreprise, $date_debut, $date_fin,);
        if ($stmt->execute()) {
            header("Location: dashboard.php?succes=experience");
            exit();
        } else {
            $erreur = "Erreur lors de l'ajout.";
        }
    } else {
        $erreur = "Le poste et l’entreprise sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une expérience</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
 
        <h2>🧳 Ajouter une expérience professionnelle</h2>

        <?php if ($succes): ?>
            <div class="message-succes"><?= $succes ?></div>
        <?php elseif ($erreur): ?>
            <div class="message-erreur"><?= $erreur ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="poste">Poste occupé</label>
            <input type="text" name="poste" id="poste" required>

            <label for="entreprise">Entreprise</label>
            <input type="text" name="entreprise" id="entreprise" required>

            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut">

            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin">

            <button type="submit">➕ Ajouter l’expérience</button>
        </form>
        <p><a href="dashboard.php">← Retour au tableau de bord</a></p>
</body>

</html>