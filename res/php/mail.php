<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("$ROOT$PATH/res/php/PHPMailer/PHPMailer.php");
require("$ROOT$PATH/res/php/PHPMailer/Exception.php");
require("$ROOT$PATH/res/php/PHPMailer/SMTP.php");

//charge le module phpmailer


$mail = new PHPMailer;
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";
// $mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Port = 587;
// $mail->SMTPSecure = "tls";
// $mail->SMTPAuth = true;

/* Outlook config: */
/*
$mail->Username = "la-mesange-bleue@outlook.com";
$mail->Host = "smtp-mail.outlook.com";
$mail->setFrom("la-mesange-bleue@outlook.com", $WEBSITE_NAME);
*/

/* Gmail config: */
/*
$mail->Username = "lamesangebleue.fr@gmail.com";
$mail->Host = "smtp.gmail.com";
$mail->setFrom("lamesangebleue.fr@gmail.com", $WEBSITE_NAME);

$mail->Password = read("res/top_secret/mail_password");
*/

$mail->Host = "ssl0.ovh.net";
$mail->Username = "noreply@thomasleveille.com";
$mail->Password = read("res/top_secret/mail_password");
$mail->setFrom("noreply@thomasleveille.com", $WEBSITE_NAME);
//configurer identifiant du mail

function send_mail($to, $subject, $body, $is_html = true) {
    global $mail;
    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->isHTML($is_html);
    $mail->Body = $body;
    $mail->Send();
} //fonction envoie mail

?>
