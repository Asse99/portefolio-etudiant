<?php
session_start();
include 'includes/db.php';

$id = $_SESSION['utilisateur_id'];
$bio = $_POST['bio'] ?? '';
$competences = $_POST['competences'] ?? '';

$sql = "UPDATE utilisateurs SET bio = ?, competences = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $bio, $competences, $id);
$stmt->execute();

header("Location: dashboard.php?succes=modif-profil");
exit();
