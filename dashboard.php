<?php

session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: auth/login.php");
    exit();
}

include 'includes/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$id_utilisateur = $_SESSION['utilisateur_id'];

// Récupération de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$utilisateur = $stmt->get_result()->fetch_assoc();




// Messages de succès/erreur
$messagesSucces = [
    'formation' => '🎓 Formaion ajouté avec succès !',
    'experience' => '🎓 Experience ajouté avec succès !',
    'diplome' => '🎓 Diplôme ajouté avec succès !',
    'certif' => '📜 Certification ajoutée avec succès !',
    'photo' => '📸 Photo mise à jour avec succès !',
    'suppr-certif' => '🗑️ Certification supprimée !',
    'modif-formation' => '✏️ Formation modifié avec succès !',
    'suppr-formation' => '🗑️ Formation supprimé !',
    'modif-experience' => '✏️ Experience modifié avec succès !',
    'suppr-experience' => '🗑️ Experience supprimé !',
    'suppr-diplome' => '🗑️ Diplôme supprimé !',
    'modif-certif' => '✏️ Certification modifiée !',
    'modif-diplome' => '✏️ Diplôme modifié avec succès !',
    'modif-profil' => '✏️ Profil modifié avec succès !',
    'modif-projet' => '✅ Projet modifié avec succès ! Tu progresses !',
    'suppr-projet' => '🗑️ Projet supprimé avec succès. Moins, mais mieux !',
    'ajout-projet' => '🚀 Nouveau projet ajouté ! Il est temps de briller !',

];
$messagesErreur = [
    'diplome' => '❌ Erreur lors de l\'ajout du diplôme.',
    'formation' => '❌ Erreur lors de l\'ajout de la formation.',
    'certif' => '❌ Erreur lors de l\'ajout de la certification.',
    'certification' => '❌ Erreur lors de l\'ajout de la certification.',
    'photo' => '❌ Erreur lors de la mise à jour de la photo.',
    'modif-profil' => '❌ Erreur lors de la modification du profil.',
    'modif-projet' => '❌ Impossible de modifier le projet. Vérifie tes champs.',
    'suppr-projet' => '❌ Échec de la suppression du projet. Essaie à nouveau.',
    'ajout-projet' => '❌ Échec de l’enregistrement du projet. Vérifie le formulaire.',
    'experience' => '❌ Erreur lors de l\'ajout de l\'expérience.',
    'modif-experience' => '❌ Erreur lors de la modification de l\'expérience.',
    'suppr-experience' => '❌ Erreur lors de la suppression de l\'expérience.'

];

$succes = $_GET['succes'] ?? null;
$erreur = $_GET['erreur'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <button class="menu-toggle" id="menuToggle">☰</button>


    <nav class="sidebar">
        <h1>👤 Mon Espace</h1>
        <ul>
            <li><a href="index.php">🏠 Accueil</a></li>
            <li><a href="ajouter_formation.php">🧠 Ajouter une formation</a></li>
            <li><a href="ajouter_experience.php">🧳 Ajouter une expérience</a></li>
            <li><a href="ajouter_diplome.php">🎓 Ajouter un diplôme</a></li>
            <li><a href="ajouter_certification.php">📜 Ajouter une certification</a></li>
            <li><a href="ajouter_projet.php">📁 Ajouter un projet</a></li>
            <li><a href="gestion_articles.php">📝 Mes article</a></li>

        </ul>
    </nav>


    <main class="contenu">
        <!-- Menu latéral -->


        <!-- Déconnexion -->
        <div class="logout-container">
            <a href="auth/logout.php">🔓 Se déconnecter</a>
        </div>

        <!-- Messages -->
        <?php if (isset($messagesSucces[$succes])): ?>
            <div class="message-succes"><?= $messagesSucces[$succes] ?></div>
        <?php endif; ?>
        <?php if (isset($messagesErreur[$erreur])): ?>
            <div class="message-erreur"><?= $messagesErreur[$erreur] ?></div>
        <?php endif; ?>

        <h2 class="log">Bienvenue, <?= htmlspecialchars($utilisateur['nom']) ?> 👋</h2>

        <!-- Carte Profil -->
        <div class="carte-profil profil-container">
            <img src="<?= htmlspecialchars($utilisateur['photo'] ?? 'assets/img/default.png') ?>" alt="Photo de profil">
            <div class="infos-profil">
                <h3><?= htmlspecialchars($utilisateur['nom']) ?></h3>
                <p><?= htmlspecialchars($utilisateur['bio'] ?? '') ?></p>
                <a class="modifier-photo" href="modifier_profil.php">🖋️ Modifier le profil</a>
            </div>
        </div>
        <!-- Formations -->
        <section>
            <h3>🧠 Mes formations</h3>
            <ul>
                <?php
                $stmt = $conn->prepare("SELECT * FROM formations WHERE utilisateur_id = ?");
                $stmt->bind_param("i", $id_utilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($formation = $result->fetch_assoc()):
                ?>
                    <li>
                        <?= htmlspecialchars($formation['titre']) ?> -
                        <?= htmlspecialchars($formation['organisme']) ?>

                        <?php
                        $debut = $formation['date_debut'];
                        $fin = $formation['date_fin'];
                        $aujourdhui = date('Y-m-d');

                        if ($debut):
                            echo "($debut → ";
                            echo (empty($fin) || $fin === $aujourdhui || $fin > $aujourdhui) ? "en cours" : $fin;
                            echo ")";
                        endif;
                        ?>
                        <br>
                        <small><?= nl2br(htmlspecialchars($formation['description'])) ?></small>
                        <br>
                        <a href="modifier_formation.php?id=<?= $formation['id'] ?>">✏️ Modifier</a>
                        <a href="supprimer_formation.php?id=<?= $formation['id'] ?>" onclick="return confirm('Supprimer cette formation ?')">🗑️ Supprimer</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
        <!-- Expériences -->
        <section>
            <h3>🧳 Mes expériences professionnelles</h3>
            <ul>
                <?php
                $stmt = $conn->prepare("SELECT * FROM experiences WHERE utilisateur_id = ?");
                $stmt->bind_param("i", $id_utilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($experience = $result->fetch_assoc()):
                ?>
                    <li>
                        <?= htmlspecialchars($experience['poste']) ?> - <?= htmlspecialchars($experience['entreprise']) ?>
                        <?php
                        $debut = $experience['date_debut'];
                        $fin = $experience['date_fin'];
                        $aujourdhui = date('Y-m-d');

                        if ($debut):
                            echo "($debut → ";
                            echo (empty($fin) || $fin === $aujourdhui || $fin > $aujourdhui) ? "en cours" : $fin;
                            echo ")";
                        endif;
                        ?>
                        <br>
                        <br>
                        <a href="modifier_experience.php?id=<?= $experience['id'] ?>">✏️ Modifier</a>
                        <a href="supprimer_experience.php?id=<?= $experience['id'] ?>" onclick="return confirm('Supprimer cette expérience ?')">🗑️ Supprimer</a>
                    </li>
                <?php endwhile; ?>
            </ul>


            <!-- Diplômes -->
            <section>
                <h3>🎓 Mes diplômes</h3>
                <ul>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM diplomes WHERE utilisateur_id = ?");
                    $stmt->bind_param("i", $id_utilisateur);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $aujourdhui = date('Y-m-d');

                    while ($d = $result->fetch_assoc()):
                        $titre = htmlspecialchars($d['titre']);
                        $etab  = htmlspecialchars($d['institution']);
                        $date  = $d['date_obtention'];
                        $affichage = (empty($date) || $date === $aujourdhui || $date > $aujourdhui) ? 'en cours' : $date;
                    ?>

                        <li>
                            <?= $titre ?> – <?= $etab ?> (<?= $affichage ?>)
                            <a href="modifier_diplome.php?id=<?= $d['id'] ?>">✏️ Modifier</a>
                            <a href="supprimer_diplome.php?id=<?= $d['id'] ?>" onclick="return confirm('Supprimer ce diplôme ?')">🗑️ Supprimer</a>
                        </li>
                    <?php endwhile; ?>
                </ul>

            </section>

            <!-- Certifications -->
            <section>
                <h3>📜 Mes certifications</h3>
                <ul>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM certifications WHERE utilisateur_id = ?");
                    $stmt->bind_param("i", $id_utilisateur);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $aujourdhui = date('Y-m-d');

                    while ($c = $result->fetch_assoc()):
                        $titre = htmlspecialchars($c['nom']);
                        $org = htmlspecialchars($c['organisme']);
                        $date = $c['date'];
                        $affichage = (empty($date) || $date === $aujourdhui || $date > $aujourdhui) ? '<span class="en-cours">en cours</span>' : htmlspecialchars($date);
                    ?>
                        <li>
                            <?= $titre ?> – <?= $org ?> (<?= $affichage ?>)
                            <a href="modifier_certification.php?id=<?= $c['id'] ?>">✏️ Modifier</a>
                            <a href="supprimer_certification.php?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer cette certification ?')">🗑️ Supprimer</a>
                        </li>
                    <?php endwhile; ?>
                </ul>

            </section>

            <!-- Projets en carrousel -->
            <section>
                <h3>💼 Mes projets Recents</h3>
                <?php
                $stmt = $conn->prepare("SELECT * FROM projets WHERE utilisateur_id = ?");
                $stmt->bind_param("i", $id_utilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                $projets = $result->fetch_all(MYSQLI_ASSOC);
                ?>

                <?php if (count($projets) === 0): ?>
                    <p>Tu n’as encore ajouté aucun projet. <a href="ajouter_projet.php">Ajoute-en un ici 🚀</a></p>
                <?php else: ?>
                    <div class="carousel-container">
                        <div class="carousel">
                            <?php foreach ($projets as $p): ?>
                                <div class="carte-projet">
                                    <h4><?= htmlspecialchars($p['titre']) ?></h4>
                                    <div class="texte-scrollable">
                                        <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
                                    </div>
                                    <?php if (!empty($p['lien'])): ?>
                                        <a href="<?= htmlspecialchars($p['lien']) ?>" target="_blank">🔗 Voir le projet</a><br>
                                    <?php endif; ?>
                                    <p>📅 <?= $p['date_projet'] ?></p>
                                    <?php if (!empty($p['image'])): ?>
                                        <img src="<?= htmlspecialchars($p['image']) ?>" alt="Image du projet">
                                    <?php endif; ?>
                                    <div class="actions-projet">
                                        <a href="modifier_projet.php?id=<?= $p['id'] ?>">✏️ Modifier</a>
                                        <a href="supprimer_projet.php?id=<?= $p['id'] ?>" onclick="return confirm('Supprimer ce projet ?')">🗑️ Supprimer</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/scripts.js"></script>

</body>

</html>