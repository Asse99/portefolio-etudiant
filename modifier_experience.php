<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: auth/login.php');
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$erreur = '';
$succes = '';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = (int)$_GET['id'];

// Récupérer l’expérience
$stmt = $conn->prepare("SELECT * FROM experiences WHERE id = ? AND utilisateur_id = ?");
$stmt->bind_param("ii", $id, $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
$exp = $result->fetch_assoc();

if (!$exp) {
    $erreur = "Expérience introuvable.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poste = trim($_POST['poste']);
    $entreprise = trim($_POST['entreprise']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($poste && $entreprise) {
        $stmt = $conn->prepare("UPDATE experiences SET poste = ?, entreprise = ?, date_debut = ?, date_fin = ? WHERE id = ? AND utilisateur_id = ?");
        $stmt->bind_param("ssssii", $poste, $entreprise, $date_debut, $date_fin, $id, $id_utilisateur);
        if ($stmt->execute()) {
            header("Location: dashboard.php?succes=modif-experience");
            exit();
        } else {
            $erreur = "Erreur lors de la modification.";
        }
    } else {
        $erreur = "Le poste et l’entreprise sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une expérience</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    
        <h2>✏️ Modifier l'expérience</h2>

        <?php if ($erreur): ?>
            <div class="message-erreur"><?= $erreur ?></div>
        <?php endif; ?>
        <?php if ($succes): ?>
            <div class="message-succes"><?= $succes ?></div>
        <?php endif; ?>

        <?php if ($exp): ?>
            <form method="post">
                <label for="poste">Poste</label>
                <input type="text" name="poste" id="poste" value="<?= htmlspecialchars($exp['poste']) ?>" required>

                <label for="entreprise">Entreprise</label>
                <input type="text" name="entreprise" id="entreprise" value="<?= htmlspecialchars($exp['entreprise']) ?>" required>

                <label for="date_debut">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" value="<?= $exp['date_debut'] ?>">

                <label for="date_fin">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" value="<?= $exp['date_fin'] ?>">

                <button type="submit">💾 Enregistrer</button>
            </form>
        <?php endif; ?>
        <p><a href="dashboard.php">← Retour au tableau de bord</a></p>
</body>

</html>