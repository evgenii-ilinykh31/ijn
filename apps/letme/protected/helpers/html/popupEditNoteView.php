<?php


namespace Helpers\Html;



require_once '___View_Interface.php';
require_once 'protected/helpers/organizers/languages.php';


use Helpers\Organizers\Languages;


class PopupEditNoteView implements ___View_Interface {

    const text = [
        Languages::russian => ['Сохранить изменения'],
        Languages::english => ['Save changes'],
        Languages::spanish => ['Guardar cambios']
    ];

    public static function getNode($languageCode): string
    {
        $translation = self::text[$languageCode];

        return <<<EOT
        <div class="popup-div">
            <div class="form">
                <div type="text" id="popupEditNote-text" contenteditable="true"></div>
                <button id="popupEditNote-button" class="pushElement">{$translation[0]}</button>
            </div>
        </div>
EOT;

    }

}