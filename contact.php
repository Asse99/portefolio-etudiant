<?php
session_start();
$titrePage = "Contact - Espace Asse";
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titrePage; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>

    <main>
        <section class="contact-section">
            <h2>✉️ Me contacter</h2>
            <p>Tu veux discuter d’un projet, poser une question ou simplement dire bonjour ? Remplis ce formulaire :</p>

            <form action="envoyer_message.php" method="POST" class="formulaire-contact">
                <input type="text" name="nom" required placeholder="Ton nom">
                <input type="email" name="email" required placeholder="Ton e-mail">
                <textarea name="message" rows="4" required placeholder="Ton message"></textarea>

                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6LdrRnErAAAAAAgNYX81SxR0E6VMNENZ1M2HlAeW"></div>

                <button type="submit">📨 Envoyer</button>
            </form>

        </section>
    </main>

    <script src="assets/js/scripts.js"></script>

</body>

</html>