<?php

namespace Helpers\Html;


require_once '___View_Interface.php';

class FindnoteView implements ___View_Interface {

    protected static $text = [
        'ru' => [
            'Введите текст для поиска в заметках',
            'Слова, которые обязательно должны быть в искомой заметке - разделяйте пробелами',
            'Дополнительное условие поиска добавляйте через запятую',
            "Комбинация: 'томат апельсин, кола лимон' найдет все заметки, которые содержат слова 'томат' и 'апельсин', и все заметки, которые содержат слова 'кола' и 'лимон'",
            'Чтобы отменить фильтр - перезагрузите страницу или отправьте пустой поисковый запрос',
            'Shift + Enter - вставить перенос строки',
            'Найти заметки'
        ],
        'en' => [
            'Enter text to search in notes',
            'Words that must be in desired note - divide by spaces',
            'Additional search conditions separate by comma',
            "Combination: 'tomato orange, cola lemon' will find all notes, which contains words 'tomato' and 'orange', and all notes, which contains words 'cola' and 'lemon'",
            'To delete filter - reload page or send empty request',
            'Shift + Enter - insert carriage symbol',
            'Find notes'
        ],
        'es' => [
            'Ingrese texto para buscar en notas',
            'Palabras que deben estar en la nota deseada - dividir por espacios',
            'Condiciones de búsqueda adicionales separadas por comas',
            "Combinación: 'tomate naranja, cola limón' encontrará todas las notas, que contienen las palabras 'tomate' y 'naranja', y todas las notas, que contienen las palabras 'cola' y 'limón'",
            'Eliminar filtro: recargar la página o enviar una solicitud vacía',
            'Shift + Enter - insertar símbolo de carro',
            'Encuentra notas'
        ]
    ];

    public static function getNode($languageCode): string
    {

        $translation = self::$text[$languageCode];

        return <<<EOT
        <div class="popup-div findNotes-div">
            <div class="form">
                <div id="findNote-text" class="placeholdered" contenteditable="true"
                     placeholder="{$translation[0]}.&#10;
                                    {$translation[1]}.&#10;
                                    {$translation[2]}.&#10;
                                    {$translation[3]}.&#10;
                                    {$translation[4]}.&#10;
                                    {$translation[5]}."></div>
                <button id="findNote-button" class="pushElement">{$translation[6]}</button>
            </div>
        </div>
EOT;

    }

}