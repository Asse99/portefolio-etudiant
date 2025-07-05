<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $organisme = $_POST['organisme'];
    $date = $_POST['date'];
    $id = $_SESSION['utilisateur_id'];

    $sql = "INSERT INTO certifications (nom, organisme, date, utilisateur_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nom, $organisme, $date, $id);
    $stmt->execute();

    header("Location: dashboard.php?succes=certif");
    exit();}
?>
<!DOCTYPE html>
<html lang="fr">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Certification</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

<h2>Ajouter une certification</h2>
<form method="POST" class="formulaire-ajout">
    <label for="nom">Nom de la certification :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="organisme">Organisme émetteur :</label>
    <input type="text" id="organisme" name="organisme" required>

    <label for="date">Date :</label>
    <input type="date" id="date" name="date" required>

    <button type="submit">Enregistrer</button>
</form>
</body>
</html>