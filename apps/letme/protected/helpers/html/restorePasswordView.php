<?php


namespace Helpers\Html;


require_once '___View_Interface.php';
require_once 'protected/helpers/organizers/languages.php';


use Helpers\Organizers\Languages;


class RestorePasswordView implements ___View_Interface {

    protected static $text = [
        Languages::russian => [
            'Отправить'
        ],
        Languages::english => [
            'Send'
        ],
        Languages::spanish => [
            'Enviar'
        ]
    ];


    public static function getNode($languageCode): string
    {
        $translation = self::$text[$languageCode];

        return <<<EOT

                    <div class="popup-div">
                        <div class="form" method="POST" action="">

                            <input id="restorePassword-email" type="email" placeholder="email" required autocomplete="off">
    
                            <button id="restorePassword-button" type="submit" class="full-color-button pushElement">
                                {$translation[0]}
                            </button>

                        </div>
                    </div>
                    
EOT;
    }

}