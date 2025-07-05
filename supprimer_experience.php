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

// Vérification que l'expérience appartient bien à l’utilisateur
$stmt = $conn->prepare("SELECT id FROM experiences WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php?erreur=experience_introuvable');
    exit();
}

// Suppression
$stmt = $conn->prepare("DELETE FROM experiences WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();

header('Location: dashboard.php?succes=suppr-experience');
exit();
