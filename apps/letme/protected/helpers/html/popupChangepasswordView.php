<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class PopupChangepasswordView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'Старый пароль',
            'Новый пароль',
            'Отправить'
        ],
        'en' => [
            'Old password',
            'New password',
            'Send'
        ],
        'es' => [
            'Contraseña anterior',
            'Nueva contraseña',
            'Enviar'
        ]
    ];

    public static function getNode($languageCode): string
    {
        $translation = self::$text[$languageCode];

        return <<<EOT

                    <div class="popup-div">
                        <div class="form" method="POST" action="">

                            <input id="oldpassword" type="text" placeholder="{$translation[0]}" required autocomplete="off">
    
                            <input id="newpassword" type="text" placeholder="{$translation[1]}" required autocomplete="off">
    
                            <button id="changepassword-button" type="submit" class="full-color-button pushElement">
                                {$translation[2]}
                            </button>

                        </div>
                    </div>
                    
EOT;
    }

}