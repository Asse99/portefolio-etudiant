<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Vérifie que l'utilisateur est un admin
$id = $_SESSION['utilisateur_id'];
$stmt = $conn->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'admin') {
    die("⛔ Accès refusé. Cette zone est réservée à l’administrateur.");
}
?>

<!-- 🎯 Partie HTML de l’espace admin -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Espace Administration – Asse</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h1>🔧 Espace administration</h1>
    <p>Bienvenue Asse. Ici tu peux gérer ton profil public, tes formations, certifications et diplômes visibles sur l’accueil.</p>

    <ul>
        <li><a href="admin_modifier_profil.php">📝 Modifier ma présentation</a></li>
        <li><a href="admin_formations.php">🎓 Mes formations</a></li>
        <li><a href="admin_certifications.php">📜 Mes certifications</a></li>
        <li><a href="admin_diplomes.php">🏅 Mes diplômes</a></li>
    </ul>
</body>

</html>