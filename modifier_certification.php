<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_certif = $_GET['id'] ?? null;

if (!$id_certif || !is_numeric($id_certif)) {
    echo "ID invalide.";
    exit();
}

// Récupération
$sql = "SELECT * FROM certifications WHERE id = ? AND utilisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_certif, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
$certif = $result->fetch_assoc();

if (!$certif) {
    echo "Certification introuvable.";
    exit();
}

// Modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $organisme = $_POST['organisme'];
    $date = $_POST['date'];

    $sql = "UPDATE certifications SET nom = ?, organisme = ?, date = ? WHERE id = ? AND utilisateur_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $nom, $organisme, $date, $id_certif, $id_utilisateur);
    $stmt->execute();

    header("Location: dashboard.php?succes=modif-certif");
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Certification</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>Modifier la certification</h2>
<form method="POST" class="formulaire-ajout">
    <label>Nom :</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($certif['nom']) ?>" required>

    <label>Organisme :</label>
    <input type="text" name="organisme" value="<?= htmlspecialchars($certif['organisme']) ?>" required>

    <label>Date :</label>
    <input type="date" name="date" value="<?= htmlspecialchars($certif['date']) ?>" required>

    <button type="submit">Mettre à jour</button>
</form>
<p><a href="dashboard.php">← Retour au tableau de bord</a></p>
<?php include 'includes/footer.php'; ?>
</body>
</html>