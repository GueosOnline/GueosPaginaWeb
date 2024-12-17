<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    function enviarEmail($email, $asunto, $cuerpo)
    {
        require_once __DIR__ . '/../config/config.php';
        require __DIR__ . '/../phpmailer/src/PHPMailer.php';
        require __DIR__ . '/../phpmailer/src/Exception.php';
        require __DIR__ . '/../phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug =  SMTP::DEBUG_OFF; //SMTP::DEBUG_OFF                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USER;                     //SMTP username
            $mail->Password   = MAIL_PASS;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients

            //Correo emisor y nombre
            $mail->setFrom(MAIL_USER, 'TIENDA GUEOS');
            //Correo receptor y nombre
            $mail->addAddress($email);     //Add a recipient

            //Contenido
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;

            //Cuerpo del correo

            $mail->CharSet = 'UTF-8';
            $mail->Body    = $cuerpo;
            $mail->setLanguage('es', __DIR__ . '/../phpmailer/src/language/phpmailer.lang-es.php');

            //Enviar correo
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo electronico: {$mail->ErrorInfo}";
            return false;
        }
    }
}
