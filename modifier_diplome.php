<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_diplome = $_GET['id'] ?? null;

if (!$id_diplome || !is_numeric($id_diplome)) {
    echo "ID invalide.";
    exit();
}

// Récupération du diplôme
$sql = "SELECT * FROM diplomes WHERE id = ? AND utilisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_diplome, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
$diplome = $result->fetch_assoc();

if (!$diplome) {
    echo "Diplôme introuvable.";
    exit();
}

// Traitement de la modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $institution = $_POST['institution'];
    $date = $_POST['date_obtention'];

    $sql = "UPDATE diplomes SET titre = ?, institution = ?, date_obtention = ? WHERE id = ? AND utilisateur_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $titre, $institution, $date, $id_diplome, $id_utilisateur);
    $stmt->execute();

    header("Location: dashboard.php?succes=modif-diplome");
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Diplôme</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>Modifier le diplôme</h2>
<form method="POST" class="formulaire-ajout">
    <label>Intitulé :</label>
    <input type="text" name="titre" value="<?= htmlspecialchars($diplome['titre']) ?>" required>

    <label>Institution :</label>
    <input type="text" name="institution" value="<?= htmlspecialchars($diplome['institution']) ?>" required>

    <label>Date d’obtention :</label>
    <input type="date" name="date_obtention" value="<?= htmlspecialchars($diplome['date_obtention']) ?>" required>

    <button type="submit">Mettre à jour</button>
</form>
<p><a href="dashboard.php">← Retour au tableau de bord</a></p>
<?php include 'includes/footer.php'; ?>
</body>
</html>