<?php

namespace Helpers\Html;


require_once '___View_Interface.php';
require_once __DIR__ . '/../organizers/oauthYandexData.php';

use Helpers\Organizers\OauthYandexData;


class FlowLoginView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'Ультралегкое приложение для заметок',
            'Двойной клик/тап - создать заметку',
            'Тройной клик/тап - поиск по заметкам',
            'Авто-сохранение при случайном закрытии',
            'Поле обязательно к заполнению',
            'Пароль',
            'Войти',
            'Зарегистрироваться',
            'ИЛИ',
            'Авторизация с помощью google id',
            'Авторизация с помощью yandex',
            'Забыли пароль?'
        ],
        'en' => [
            'Ultralight app for creating notes',
            'Double click/tap - create note',
            'Triple click/tap - search in notes',
            'Auto-saving due accidental closing',
            'Field is required',
            'Password',
            'Login',
            'Register',
            'OR',
            'Google id authorization',
            'Yandex authorization',
            'Forgot password?'
        ],
        'es' => [
            'Aplicación ultraligera para crear notas',
            'App ultraligera para crear notas',
            'Triple clic / toque - buscar en notas',
            'Auto-guardado por cierre accidental',
            'Se requiere campo',
            'Contraseña',
            'Iniciar sesión',
            'Registrarse',
            'O',
            'Autorización de google id',
            'Autorización de yandex',
            '¿Se te olvidó tu contraseña?'
        ]
    ];

    public static function getNode($languageCode): string
    {

        $translation = self::$text[$languageCode];

        $oauthYandex = new OauthYandexData();

        return <<<EOT
                    <div id="promo">
                        <p>• {$translation[0]}</p>
                        <p>• {$translation[1]}</p>
                        <p>• {$translation[2]}</p>
                        <p>• {$translation[3]}</p>
                    </div>

                    <div id="regForm" class="form login" method="POST" action="">

                        <input id="email" type="email" class="login" placeholder="Email" title="{$translation[4]}" required>

                        <input id="password" type="password" class="login" placeholder="{$translation[5]}"  title="{$translation[4]}" required>

                        <button id="authorizeButton" type="submit" class="full-color-button">
                            {$translation[6]}
                        </button>

                        <button id="registerButton" type="submit" class="border-color-button">
                            {$translation[7]}
                        </button>

                        <div id="login-border">
                            <div class="line"></div>
                            <div class="text">{$translation[8]}</div>
                            <div class="line"></div>
                        </div>

                        <div id="oauth-block">
                            <a href='{$oauthYandex->getUrl()}'><img src="/public/images/logo-yandex.svg" title="{$translation[10]}"></a>
                        </div>

                        <div id="forgot-password">
                            {$translation[11]}
                        </div>

                    </div>
EOT;

    }

}