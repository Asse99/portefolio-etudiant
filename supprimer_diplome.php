<?php
session_start();
include 'includes/db.php';
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_diplome = $_GET['id'];

$sql = "DELETE FROM diplomes WHERE id = ? AND utilisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_diplome, $id_utilisateur);
$stmt->execute();

header("Location: dashboard.php?succes=suppr-diplome");
exit();

// Fermer la connexion à la base de données