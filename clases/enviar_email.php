<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //SMTP::DEBUG_OFF                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = MAIL_USER;                     //SMTP username
    $mail->Password   = MAIL_PASS;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Correo emisor y nombre
    $mail->setFrom(MAIL_USER, 'TIENDA CDP');
    //Correo receptor y nombre
    $mail->addAddress('mianrobe321@gmail.com', 'Contacto GMB');     //Add a recipient
    //enviar copia correo
    $mail->addReplyTo('mianrobe321@gmail.com');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Correo GUEOS';

    $cuerpo = '<h4>Esto es una prueba</h4>';
    $cuerpo .= '<p>mas texto para rellenar el correo</p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'parte final de correo';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico: {$mail->ErrorInfo}";
}
