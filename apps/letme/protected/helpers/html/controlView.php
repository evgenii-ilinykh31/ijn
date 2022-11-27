<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class ControlView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'Двойной клик/тап - создать заметку',
            'Текст сохраняется при случайном закрытии',
            'Тройной клик/тап - поиск по заметкам'
        ],
        'en' => [
            'Double click/tap - create note',
            'Text saving due accidental closing',
            'Triple click/tap - search in notes'
        ],
        'es' => [
            'Doble clic / toque - crear nota',
            'Guardado de texto por cierre accidental',
            'Triple clic / toque - buscar en notas'
        ]
    ];

    public static function getNode($languageCode): string
    {

        $translation = self::$text[$languageCode];

        return <<<EOT
        <div class="popup-div control-div">
            <div class="form">
                <span>
                    {$translation[0]}<br \>
                    {$translation[1]}
                </span>
                <span>{$translation[2]}</span>
            </div>
        </div>
EOT;

    }

}