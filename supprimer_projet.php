<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];
$id_projet = $_GET['id'] ?? null;

if ($id_projet) {
    // Vérifie que le projet appartient bien à l'utilisateur
    $stmt = $conn->prepare("SELECT image FROM projets WHERE id = ? AND utilisateur_id = ?");
    $stmt->bind_param("ii", $id_projet, $id_utilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($projet = $result->fetch_assoc()) {
        // Supprime l'image associée si elle existe
        if (!empty($projet['image']) && file_exists($projet['image'])) {
            unlink($projet['image']);
        }

        // Supprime le projet de la base
        $stmt = $conn->prepare("DELETE FROM projets WHERE id = ? AND utilisateur_id = ?");
        $stmt->bind_param("ii", $id_projet, $id_utilisateur);
        $stmt->execute();

        header("Location: dashboard.php?succes=suppr-projet");
        exit();
    }
}

header("Location: dashboard.php?erreur=suppr-projet");
exit();
?>