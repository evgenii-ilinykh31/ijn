<?php


namespace Helpers\Html;


require_once '___View_Interface.php';
require_once 'protected/helpers/organizers/languages.php';

use Helpers\Organizers\Languages;


class UndoDeleteView implements ___View_Interface {

    const text = [
        Languages::russian => [
            'Заметка удалена',
            'Отмена'
        ],
        Languages::english => [
            'Note deleted',
            'Cancel'
        ],
        Languages::spanish => [
            'Nota eliminada',
            'Cancelar'
        ]
    ];

    public static function getNode($languageCode): string
    {
        $translation = self::text[$languageCode];

        return <<<EOT
        <div></div>
        <img src="/public/images/trash.svg">
        <span>{$translation[0]}</span>
        <div><span id="undoDeleteButton">{$translation[1]}</span></div>
EOT;


    }
}