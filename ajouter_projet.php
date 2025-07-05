<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id = $_SESSION['utilisateur_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $lien = $_POST['lien'] ?? '';
    $date_projet = $_POST['date_projet'] ?? '';
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO projets (utilisateur_id, titre, description, lien, image, date_projet)
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id, $titre, $description, $lien, $image_path, $date_projet);
    $stmt->execute();
    
    header("Location: dashboard.php?succes=ajout-projet");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <h1>💼 Ajouter un nouveau projet</h1>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre du projet" required><br><br>
        <textarea name="description" placeholder="Description du projet" required></textarea><br><br>
        <input type="url" name="lien" placeholder="Lien GitHub ou site"><br><br>
        <input type="file" name="image" accept="image/*"><br><br>
        <input type="date" name="date_projet" required><br><br>
        <button type="submit">🚀 Ajouter</button>
    </form>

    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

</body>

</html>