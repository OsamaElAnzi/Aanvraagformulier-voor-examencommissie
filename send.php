<?php
require 'vendor/autoload.php';
require_once 'generate.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Maak een nieuw PHPMailer object
$mail = new PHPMailer(true);

try {
    // Server instellingen
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';  // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';  // SMTP gebruikersnaam
    $mail->Password = 'your_password';  // SMTP wachtwoord
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Ontvangers
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('to@example.com', 'Ontvanger');
    $mail->addCC('your_email@example.com'); // Jouw e-mailadres in CC

    // Bijlagen
    $pdfPath = __DIR__ . '/voorbeeld.pdf';
    $mail->addAttachment($pdfPath, 'voorbeeld.pdf');

    // Inhoud
    $mail->isHTML(true);
    $mail->Subject = 'Hier is je PDF';
    $mail->Body    = 'Hier is de PDF die je hebt aangevraagd.';

    $mail->send();
    echo 'Bericht is verstuurd';
} catch (Exception $e) {
    echo "Bericht kon niet verstuurd worden. Mailer Error: {$mail->ErrorInfo}";
}
