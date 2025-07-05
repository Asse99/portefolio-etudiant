<?php
include 'includes/header.php';
require_once 'includes/db.php';

// Charger le profil de l’admin
$profil = null;
$diplomes = $certifs = $formations = $experiences = $projets_publics = [];

$sql = "SELECT id, nom, bio, photo FROM utilisateurs WHERE role = 'admin' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $profil = $result->fetch_assoc();
    $admin_id = $profil['id'];

    // Diplômes
    $stmt = $conn->prepare("SELECT titre, institution, date_obtention FROM diplomes WHERE utilisateur_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $diplomes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Expériences
    $stmt = $conn->prepare("SELECT poste, entreprise, date_debut, date_fin FROM experiences WHERE utilisateur_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $experiences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Certifications
    $stmt = $conn->prepare("SELECT nom, organisme, date FROM certifications WHERE utilisateur_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $certifs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Formations
    $stmt = $conn->prepare("SELECT titre, organisme, date_debut, date_fin FROM formations WHERE utilisateur_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $formations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Projets publics
    $sql = "SELECT * FROM projets WHERE utilisateur_id = ? ORDER BY date_projet DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $projets_publics = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil – Espace Asse</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header class="accueil-header">
        <h1>Bienvenue sur l’espace d’Asse</h1>
        <p>Développeur web, passionné de cybersécurité et adepte du taekwondo 🥋💻</p>

        <?php if ($profil): ?>
            <div class="profil-container sticky-profil">
                <div class="carte-profil accueil">
                    <img src="<?= htmlspecialchars($profil['photo'] ?? 'assets/img/default.png') ?>" alt="Photo de profil">
                    <div class="infos-profil">
                        <h3><?= htmlspecialchars($profil['nom']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($profil['bio'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <main class="accueil-contenu">
        <div class="ligne-bloc">
            <div class="bloc-colonne">
                <h3>📚 Formations</h3>
                <ul>
                    <?php foreach ($formations as $f): ?>
                        <li>
                            <?= htmlspecialchars($f['titre']) ?> – <?= htmlspecialchars($f['organisme']) ?>
                            <?php
                            $debut = $f['date_debut'];
                            $fin = $f['date_fin'];
                            $aujourdhui = date('Y-m-d');

                            if ($debut):
                                echo " ($debut → ";
                                echo (empty($fin) || $fin > $aujourdhui) ? "en cours" : $fin;
                                echo ")";
                            endif;
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bloc-colonne">
                <h3>💼 Expériences</h3>
                <ul>
                    <?php foreach ($experiences as $e): ?>
                        <li>
                            <?= htmlspecialchars($e['poste']) ?> – <?= htmlspecialchars($e['entreprise']) ?>
                            <?php
                            $debut = $e['date_debut'];
                            $fin = $e['date_fin'];
                            $aujourdhui = date('Y-m-d');

                            if ($debut):
                                echo " ($debut → ";
                                echo (empty($fin) || $fin > $aujourdhui) ? "en cours" : $fin;
                                echo ")";
                            endif;
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="ligne-bloc">
            <div class="bloc-colonne">
                <h3>🎓 Diplômes</h3>
                <ul>
                    <?php foreach ($diplomes as $d): ?>
                        <li>
                            <?= htmlspecialchars($d['titre']) ?> – <?= htmlspecialchars($d['institution']) ?> (<?= $d['date_obtention'] ?? 'en cours' ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bloc-colonne">
                <h3>📜 Certifications</h3>
                <ul>
                    <?php foreach ($certifs as $c): ?>
                        <li>
                            <?= htmlspecialchars($c['nom']) ?> – <?= htmlspecialchars($c['organisme']) ?>
                            (<?= empty($c['date']) ? '<span class="en-cours">en cours</span>' : htmlspecialchars($c['date']) ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <h2>💼 Projets Récents</h2>
        <?php if (empty($projets_publics)): ?>
            <p>Aucun projet enregistré pour le moment.</p>
        <?php else: ?>
            <div class="carousel-container">
                <div class="carousel">
                    <?php foreach ($projets_publics as $p): ?>
                        <div class="carte-projet">
                            <h4><?= htmlspecialchars($p['titre']) ?></h4>
                            <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                            <?php if (!empty($p['lien'])): ?>
                                <a href="<?= htmlspecialchars($p['lien']) ?>" target="_blank">🔗 Voir le projet</a><br>
                            <?php endif; ?>
                            <p>📅 <?= htmlspecialchars($p['date_projet']) ?></p>
                            <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="Image du projet">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="accueil-footer">
        <p>&copy; <?= date("Y") ?> Espace Asse – Code, Défense et Résilience</p>
    </footer>

</body>

</html>