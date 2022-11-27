<?php

namespace Helpers;

require_once 'organizers/languages.php';
require_once 'organizers/constants.php';

use Helpers\Organizers\Languages;
use Helpers\Organizers\Constants;
use Helpers\Models\Users;

class Email {

    protected const validError = [
        Languages::russian => 'Нужен корректный email',
        Languages::english => 'Valid email needed',
        Languages::spanish => 'Se necesita un email válido'
    ];

    protected const existError = [
        Languages::russian => 'Такой email уже зарегистрирован',
        Languages::english => 'Email is already registered',
        Languages::spanish => 'El email ya está registrado'
    ];

    public static function isValid($email): bool
    {

        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }

        return true;
    }


    public static function getValidError(string $language = Languages::english): string
    {
        return self::validError[$language];
    }


    public static function isExist($email): bool
    {
        require_once 'models/users.php';

        if ( ! Users::getId($email))
        {
            return false;
        }

        return true;
    }


    public static function getExistError(string $language = Languages::english): string
    {
        return self::existError[$language];
    }

}

?>
