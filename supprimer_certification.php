<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_certif = $_GET['id'];

// Sécurité : on supprime uniquement si ça appartient à l'utilisateur connecté
$sql = "DELETE FROM certifications WHERE id = ? AND utilisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_certif, $id_utilisateur);
$stmt->execute();

header("Location: dashboard.php?succes=suppr-certif");
exit();
?>