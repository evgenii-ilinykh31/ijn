<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class TakenoteView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'Введите текст заметки',
            'Shift + Enter - вставить перенос строки',
            'Сохранить'
        ],
        'en' => [
            'Enter text of note',
            'Shift + Enter - insert carriage symbol',
            'Save'
        ],
        'es' => [
            'Ingrese el texto de la nota',
            'Shift + Enter - insertar símbolo de carro',
            'Salvar'
        ]
    ];


    public static function getNode($languageCode): string
    {
        $translation = self::$text[$languageCode];

        return <<<EOT
        <div class="popup-div takeNote-div">
            <div class="form">
                <div type="text" id="takeNote-text" contenteditable="true"
                    placeholder="{$translation[0]}&#10;
                                 {$translation[1]}"></div>
                <button id="takeNote-button" class="pushElement">{$translation[2]}</button>
            </div>
        </div>
EOT;

    }

}