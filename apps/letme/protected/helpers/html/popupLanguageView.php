<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class PopupLanguageView implements ___View_Interface {

    protected static $block = [
        'ru' => [
            'Русский',
            'flag-russia.png'
        ],
        'en' => [
            'English',
            'flag-usa.png'
        ],
        'es' => [
            'Española',
            'flag-spain.png'
        ]
    ];

    public static function getNode($languageCode): string
    {
        $node = '';
        $isActive = '';

        foreach (self::$block as $currentCode => $data)
        {
            if ($languageCode === $currentCode)
            {
                $isActive = 'active';
            }
            else
            {
                $isActive = '';
            }



            $node .= <<<EOT
                <button class="language {$isActive}" data-languagecode="{$currentCode}">
                    <span>{$data[0]}</span>
                    <img src="/public/images/{$data[1]}">
                </button>
EOT;

        }


        return $node;
    }

}