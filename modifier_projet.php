<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_projet = $_GET['id'] ?? null;
$message = "";

// Chargement du projet
if ($id_projet) {
    $stmt = $conn->prepare("SELECT * FROM projets WHERE id = ? AND utilisateur_id = ?");
    $stmt->bind_param("ii", $id_projet, $id_utilisateur);
    $stmt->execute();
    $result = $stmt->get_result();
    $projet = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $lien = $_POST['lien'] ?? '';
    $date_projet = $_POST['date_projet'] ?? '';
    $image_path = $projet['image'];

    // Si une nouvelle image est envoyée
    if (!empty($_FILES['image']['name'])) {
        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }
        $upload_dir = 'uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("UPDATE projets SET titre = ?, description = ?, lien = ?, date_projet = ?, image = ? 
                            WHERE id = ? AND utilisateur_id = ?");
    $stmt->bind_param("ssssssi", $titre, $description, $lien, $date_projet, $image_path, $id_projet, $id_utilisateur);
    $stmt->execute();

    header("Location: dashboard.php?succes=modif-projet");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier le projet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <h2>✏️ Modifier le projet</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required><br>
        <textarea name="description" required><?= htmlspecialchars($projet['description']) ?></textarea><br>
        <input type="url" name="lien" value="<?= htmlspecialchars($projet['lien']) ?>"><br>
        <input type="date" name="date_projet" value="<?= htmlspecialchars($projet['date_projet']) ?>" required><br>
        <input type="file" name="image" accept="image/*"><br>
        <?php if (!empty($projet['image'])): ?>
            <img src="<?= htmlspecialchars($projet['image']) ?>" width="200"><br>
        <?php endif; ?>
        <button type="submit">💾 Enregistrer</button>
    </form>

    <p><a href="dashboard.php">← Retour au tableau de bord</a></p>

</body>

</html>