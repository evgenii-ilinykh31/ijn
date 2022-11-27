<?php

namespace Helpers\Email;

use Helpers\Email\PHPMailer\PHPMailer;
use Helpers\Email\PHPMailer\SMTP;
use Helpers\Email\PHPMailer\Exception;

require_once 'phpMailer/PHPMailer.php';
require_once 'phpMailer/SMTP.php';
require_once 'phpMailer/Exception.php';

class Email {

    const host = 'ssl://smtp.mail.ru';
    const userName = 'notify@ijn.su';
    const password = 'Cvbnm!988';
    const DKIM_domain = 'ijn.su';
    const from = 'notify@ijn.su';
    const fromName = 'LetMe Project';

    public static function send($email, $subject, $text)
    {

        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = false;                      // Enable verbose debug output
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = self::host;                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = self::userName;                     // SMTP username
        $mail->Password = self::password;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        $mail->DKIM_domain = self::DKIM_domain;
        $mail->DKIM_private = 'protected/dkim/mail.private';
        $mail->DKIM_selector = 'phpmailer';
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;


        //Recipients
        $mail->setFrom(self::from, self::fromName);
        $mail->addAddress($email);     // Add a recipient


        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $text;

        $mail->send();
    }

}

?>