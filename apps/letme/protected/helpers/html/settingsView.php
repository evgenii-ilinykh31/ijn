<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class SettingsView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'О приложении',
            'Как пользоваться',
            'Язык',
            'Сменить пароль',
            'Выйти'
        ],
        'en' => [
            'About app',
            'How to use',
            'Language',
            'Change password',
            'Exit'
        ],
        'es' => [
            'Acerca de la ap',
            'Cómo utilizar',
            'Idioma',
            'Cambia la contrasena',
            'Salida'
        ]
    ];


    public static function getNode($languageCode): string
    {

        $translation = self::$text[$languageCode];

        return <<<EOT
        <div class="popup-div settings-div">
            <div class="form">
                <button id="m-ijn">{$translation[0]}</button>
                <button id="control">{$translation[1]}</button>
                <button id="language-settings">
                    <span>{$translation[2]}</span>
                    <img src="/public/images/logo-language-dark.svg">
                </button>
                <button id="changepassword">{$translation[3]}</button>
                <button id="exit">{$translation[4]}</button>
            </div>
        </div>
EOT;

    }

}