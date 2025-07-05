<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = (int)$_GET['id'];

// Vérifier si la formation appartient à l'utilisateur
$stmt = $conn->prepare("SELECT id FROM formations WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php?erreur=suppression');
    exit();
}

// Supprimer la formation
$stmt = $conn->prepare("DELETE FROM formations WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();

header('Location: dashboard.php?succes=suppr-formation');
exit();
?>