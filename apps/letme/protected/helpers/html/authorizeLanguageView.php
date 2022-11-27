<?php


namespace Helpers\Html;


require_once '___View_Interface.php';

class AuthorizeLanguageView implements ___View_Interface {

    protected static $block = [
        'ru' => [
            'flag-russia.png'
        ],
        'en' => [
            'flag-usa.png'
        ],
        'es' => [
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

            $node .= '<img src="/public/images/' . $data[0] . '" class="language ' . $isActive . '" data-languagecode="' . $currentCode . '">';
        }


        return $node;
    }


}