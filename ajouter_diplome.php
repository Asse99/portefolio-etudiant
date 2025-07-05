<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $institution = $_POST['institution'];
    $date_obtention = $_POST['date_obtention'];
    $id = $_SESSION['utilisateur_id'];

    $sql = "INSERT INTO diplomes (titre, institution, date_obtention, utilisateur_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $titre, $institution, $date_obtention, $id);
    $stmt->execute();

    header("Location: dashboard.php?succes=diplome");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Diplôme</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <h2>Ajouter un diplôme</h2>
    <form method="POST" class="formulaire-ajout">
        <label for="titre">Intitulé du diplôme :</label>
        <input type="text" id="titre" name="titre" required>

        <label for="institution">Établissement :</label>
        <input type="text" id="institution" name="institution" required>

        <label for="date_obtention">Date d’obtention :</label>
        <input type="date" id="date_obtention" name="date_obtention" required>
        <button type="submit">Enregistrer</button>
    </form>
    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

</body>

</html>