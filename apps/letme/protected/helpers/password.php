<?php

namespace Helpers;


require_once 'organizers/languages.php';
require_once 'organizers/constants.php';
require_once 'models/users.php';

use Helpers\Organizers\Languages;
use Helpers\Organizers\Constants;
use Helpers\Models\Users;


class Password {

    protected const userErrors = [
        Languages::english => 'password - ' . Constants::minPasswordLength . ' symbols or more',
        Languages::russian => 'пароль - ' . Constants::minPasswordLength . ' символа или больше',
        Languages::spanish => 'contraseña - ' . Constants::minPasswordLength . ' símbolos o más'
    ];


    public static function isSafe($password): bool
    {

        if (strlen($password) < Constants::minPasswordLength)
        {
            return false;
        }

        return true;

    }


    public static function getSafetyError(string $language): string
    {
        return self::userErrors[$language];
    }


    public static function isPasswordMatches(string $verifyingPassword, string $userId): bool
    {
        if ( ! password_verify($verifyingPassword, Users::getPassword($userId)))
        {
            return false;
        }

        return true;
    }

    public static function setNewPassword(string $userId, string $newPassword): void
    {
        Users::setNewPassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
    }
}

?>