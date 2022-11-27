<?php


namespace Helpers\Html;


require_once '/home/ijn/apps/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/languages.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;


class RestorePasswordEmailView {

    const text = [
        Languages::russian => [
            'Восстановление пароля',
            'Здравствуйте',
            'Вы получили это письмо, потому что запросили восстановление пароля для приложения с заметками',
            'Чтобы открыть окно ввода нового пароля - нажмите на кнопку ниже, ссылка действительна в течении 15 минут',
            'Новый пароль',
            'Обещаем, что не будем присылать других писем',
            'Кроме запросов на восстановление пароля :-)',
            'LetMe - простой способ создавать ваши заметки',
            'Сделано в'
        ],
        Languages::english => [
            'Password restore',
            'Hello',
            'You have got this letter, because of password restoring request for access to making notes application',
            'To be redirected to create new password window - push the button below, link valid for 15 minutes',
            'New password',
            'We promise, that never will send you another letters',
            'Excepts for password restore requests :-)',
            'LetMe - easy way to create your notes',
            'Made in'
        ],
        Languages::spanish => [
            'Restaurar contraseña',
            'Hola',
            'Recibió esta carta, debido a la solicitud de restauración de contraseña para acceder a la aplicación de notas',
            'Para ser redirigido para crear una nueva ventana de contraseña, presione el botón de abajo, enlace válido por 15 minutos',
            'Nueva contraseña',
            'Te lo prometemos, que nunca te enviaremos más cartas',
            'Excepciones para solicitudes de restauración de contraseña :-)',
            'LetMe: una forma fácil de crear sus notas',
            'Hecho en'
        ]
    ];

    public function __construct()
    {

    }

    public static function getEmail($languageCode, $confirmationLink): string
    {
        $translation = self::text[$languageCode];
        $letMeIjn = Constants::letmeIjn;

        return <<<EOT

<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>LetMe</title>
    <style>

        /* -------------------------------------
            INLINED WITH htmlemail.io/inline
        ------------------------------------- */
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: Helvetica !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: Helvetica;
                font-weight: inherit;
                line-height: inherit;
            }

            .btn-primary a:hover {
                background-color: rgba(60, 207, 255, 1) !important;
            }
        }
    </style>
</head>
<body class=""
      style="background-color: #f6f6f6; font-family: Helvetica; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<table border="0" cellpadding="0" cellspacing="0" class="body"
       style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
    <tr>
        <td style="font-family:  Helvetica; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container"
            style="font-family:  Helvetica; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
            <div class="content"
                 style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                <!-- START CENTERED WHITE CONTAINER -->
                <span class="preheader"
                      style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">LetMe {$translation[0]}</span>
                <table class="main"
                       style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper"
                            style="font-family:  Helvetica; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                            <table border="0" cellpadding="0" cellspacing="0"
                                   style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                <tr>
                                    <td style="font-family:  Helvetica; font-size: 14px; vertical-align: top;">
                                        <p style="font-family: Helvetica; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                            {$translation[1]},</p>
                                        <p style="font-family:  Helvetica; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                            {$translation[2]}: <a
                                                href="{$letMeIjn}" target="_blank"
                                                style="text-decoration: none; color:rgb(60, 207, 255);">LetMe</a></p>
                                        <p style="font-family:  Helvetica; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                            {$translation[3]}:</p>
                                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary"
                                               style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                            <tbody>
                                            <tr>
                                                <td align="left"
                                                    style="font-family:  Helvetica; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                           style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                        <tbody>
                                                        <tr>
                                                            <td style="font-family:  Helvetica; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;">
                                                                <a href="{$confirmationLink}" target="_blank"
                                                                   style="display: inline-block; color: #ffffff; background-color: rgba(60, 207, 255, 0.75); border: none; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: 400; margin: 0; padding: 12px 25px; text-transform: capitalize;">{$translation[4]}</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>


                                        <p style="font-family:  Helvetica; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                            {$translation[5]}</p>
                                        <p style="font-family:  Helvetica; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                            {$translation[6]}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>

                <!-- START FOOTER -->
                <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                        <tr>
                            <td class="content-block powered-by"
                                style="font-family:  Helvetica; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                {$translation[7]}
                            </td>
                        </tr>

                        <tr>
                            <td class="content-block powered-by"
                                style="font-family:  Helvetica; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                {$translation[8]} <a href="http://ijn.su"
                                             style="color: #999999; font-size: 12px; text-align: center; text-decoration: none; color:rgb(15, 15, 15)">IJN</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td style="font-family:  Helvetica; font-size: 14px; vertical-align: top;">&nbsp;</td>
    </tr>
</table>
</body>
</html>

EOT;

    }

}