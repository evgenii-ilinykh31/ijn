<?php


namespace Helpers\Html;


require_once 'protected/helpers/html/___View_Interface.php';
require_once 'protected/helpers/organizers/constants.php';
require_once 'protected/helpers/organizers/languages.php';


use Helpers\Html\___View_Interface;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;


class PasswordGetRestorerView implements ___View_Interface {

    const text = [
        Languages::russian => [
            'ru',
            'Страница восстановления пароля',
            'Введите Ваш email',
            'Введите новый пароль',
            'Отправить'
        ],
        Languages::english => [
            'en',
            'Restore password page',
            'Insert your email',
            'Insert new password',
            'Send'
        ],
        Languages::spanish => [
            'es',
            'Restaurar la página de contraseña',
            'Inserta tu email',
            'Insertar nueva contraseña',
            'Enviar'
        ]
    ];

    public static function getNode($languageCode): string
    {
        $translation = self::text[$languageCode];

        return <<<EOT

<!DOCTYPE html>
<html lang="{$translation[0]}">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    <title>LetMe</title>

    <!---add icon link-->
    <link rel="icon" href="/public/images/logo.svg" type="image/x-icon">

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="preload" href="/public/fonts/nunito-regular-webfont.woff2" as="font" type="font/woff2"
          crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/nunito-semibold-webfont.woff2" as="font" type="font/woff2"
          crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/nunito-bold-webfont.woff2" as="font" type="font/woff2"
          crossorigin="anonymous">

    <!-- Styles -->
    <link rel="preload" href="/public/css/passwordRestorer.css" as="style">
    <link rel="preload" href="/public/css/m.css" as="style">
    <link rel="preload" href="/public/css/common.css" as="style">
    <link rel="preload" href="/public/css/popup.css" as="style">

    <link href="/public/css/passwordRestorer.css" rel="stylesheet">
    <link href="/public/css/m.css" rel="stylesheet">
    <link href="/public/css/common.css" rel="stylesheet">
    <link href="/public/css/popup.css" rel="stylesheet">

    <!--Images-->

</head>


<body class="preload">


<div id="app" class="login">
    <div id="content">
        <span id="header">{$translation[1]}</span>
        <input type="text" id="email" placeholder="{$translation[2]}" autocomplete="off">
        <input type="text" id="newPassword" placeholder="{$translation[3]}" autocomplete="off">
        <button id="send" class="full-color-button">{$translation[4]}</button>
        <span id="message"></span>
    </div>
</div>

<script type="text/javascript" src="/public/js/passwordRestorer.js?v=1" charset="utf-8" defer> </script>
</body>
</html>

EOT;

    }

}