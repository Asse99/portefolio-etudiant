<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($nom) || empty($email) || empty($message)) {
    $_SESSION['contact_status'] = ['type' => 'erreur', 'texte' => 'Tous les champs sont obligatoires.'];
    header('Location: contact.php');
    exit;
}

$mail = new PHPMailer(true);

try {
    // Config SMTP (exemple Gmail)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'assendir1999@gmail.com'; // ← Ton adresse Gmail
    $mail->Password   = 'autw wmbg ewup hckx';      // ← Mot de passe applicatif
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Infos e-mail
    $mail->setFrom($email, $nom);
    $mail->addAddress('assendir1999@gmail.com'); // ← Où tu veux recevoir les messages
    $mail->Subject = "📬 Nouveau message de $nom";
    $mail->Body    = "Nom : $nom\nEmail : $email\n\n$message";

    $mail->send();
    $_SESSION['contact_status'] = ['type' => 'succes', 'texte' => 'Message envoyé avec succès !'];
} catch (Exception $e) {
    $_SESSION['contact_status'] = ['type' => 'erreur', 'texte' => "Erreur : {$mail->ErrorInfo}"];
}

header('Location: contact.php');
exit;
