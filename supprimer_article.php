<?php
require 'includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    die("⛔ ID article non valide.");
}

// Préparer la requête de suppression
$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: gestion_articles.php?succes=suppr-article');
    // Optionnel : vous pouvez stocker un message de succès dans la session 
    exit;
} else {
    echo "❌ Erreur lors de la suppression : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>