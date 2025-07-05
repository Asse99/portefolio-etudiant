<?php
session_start();
include 'includes/db.php';
$id = $_SESSION['utilisateur_id'];

if (isset($_POST['upload']) && isset($_FILES['photo'])) {
    $dossier_upload = "uploads/";
    $nom_fichier = time() . "_" . basename($_FILES["photo"]["name"]); // éviter les doublons
    $chemin = $dossier_upload . $nom_fichier;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin)) {
        $sql = "UPDATE utilisateurs SET photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $chemin, $id);
        $stmt->execute();
        header("Location: dashboard.php?succes=modif-profil");
        exit();
    } else {
        $message = "Erreur lors de l'envoi du fichier.";
    }
}

if (isset($_POST['update_nom'])) {
    $nouveau_nom = trim($_POST['nom']);
    if (!empty($nouveau_nom)) {
        $sql = "UPDATE utilisateurs SET nom = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nouveau_nom, $id);
        $stmt->execute();
        header("Location: dashboard.php?succes=modif-profil");
        exit();
    } else {
        $message = "Le nom ne peut pas être vide.";
    }
}

if (isset($_POST['update_bio'])) {
    $nouvelle_bio = $_POST['bio'];
    $sql = "UPDATE utilisateurs SET bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nouvelle_bio, $id);
    $stmt->execute();
    header("Location: dashboard.php?succes=modif-profil");
    exit();
}

$message = "";

// Récupérer les infos actuelles de l'utilisateur pour pré-remplir le formulaire
$sql = "SELECT nom, bio, photo FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$utilisateur = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_nom = trim($_POST['nom'] ?? $utilisateur['nom']);
    $nouvelle_bio = $_POST['bio'] ?? $utilisateur['bio'];
    $nouvelle_photo = $utilisateur['photo'];

    // Gestion de la photo si un fichier est envoyé
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $dossier_upload = "uploads/";
        $nom_fichier = time() . "_" . basename($_FILES["photo"]["name"]);
        $chemin = $dossier_upload . $nom_fichier;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin)) {
            $nouvelle_photo = $chemin;
        } else {
            $message = "Erreur lors de l'envoi du fichier.";
        }
    }

    // Mise à jour des champs
    $sql = "UPDATE utilisateurs SET nom = ?, bio = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nouveau_nom, $nouvelle_bio, $nouvelle_photo, $id);
    if ($stmt->execute()) {
        header("Location: dashboard.php?succes=modif-profil");
        exit();
    } else {
        $message = "Erreur lors de la mise à jour du profil.";
    }
}
if (isset($_POST['update_nom']) || isset($_POST['update_bio'])) {
    $nouveau_nom = trim($_POST['nom'] ?? $utilisateur['nom']);
    $nouvelle_bio = $_POST['bio'] ?? $utilisateur['bio'];

    $sql = "UPDATE utilisateurs SET nom = ?, bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nouveau_nom, $nouvelle_bio, $id);
    if ($stmt->execute()) {
        header("Location: dashboard.php?succes=modif-profil");
        exit();
    } else {
        $message = "Erreur lors de la mise à jour du profil.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2 class="log">Modifier votre profil</h2>

        <form method="POST" enctype="multipart/form-data">

            <label for="nom_all">Nom :</label>
            <input type="text" id="nom_all" name="nom" placeholder="nouveau nom" value="<?= htmlspecialchars($utilisateur['nom'] ?? '') ?>" required>
            <label for="bio_all">Bio :</label>
            <textarea id="bio_all" name="bio" rows="3" placeholder="nouveau bio"><?= htmlspecialchars($utilisateur['bio'] ?? '') ?></textarea>
            <label for="photo_all">Photo de profil :</label>
            <input type="file" id="photo_all" name="photo" accept="image/*">
            <button type="submit" name="update_all">Mettre à jour</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
<p><?= $message ?? '' ?></p>