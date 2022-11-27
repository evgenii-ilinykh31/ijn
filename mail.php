<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'protected/phpmailer/Exception.php';
require_once 'protected/phpmailer/PHPMailer.php';
require_once 'protected/phpmailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'ssl://smtp.mail.ru';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'notify@ijn.su';                     // SMTP username
    $mail->Password   = 'Cvbnm!988';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    $mail->DKIM_domain = 'ijn.su';
    $mail->DKIM_private = 'protected/dkim/mail.private';
    $mail->DKIM_selector = 'phpmailer';
    $mail->DKIM_passphrase = '';
    $mail->DKIM_identity = $mail->From;


    //Recipients
    $mail->setFrom('notify@ijn.su', 'IJN SEO');
    $mail->addAddress('iesbk@bk.ru');     // Add a recipient
    $mail->addAddress('evgilinykh@gmail.com');               // Name is optional
//    $mail->addReplyTo('evg@ijn.su', 'IJN SEO');


    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Subject';
    $mail->Body    = '<b>My</b> first php mail';

    print '<br><br>ok<br><br>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


print 'ok';

?>