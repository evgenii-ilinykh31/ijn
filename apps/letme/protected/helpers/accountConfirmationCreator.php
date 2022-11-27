<?php

namespace Helpers;


require_once 'models/confirmations.php';
require_once 'organizers/constants.php';
require_once 'organizers/languages.php';
require_once 'workers/utf8RandomString.php';

use Helpers\Models\Confirmations;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Workers\Utf8RandomString;

class AccountConfirmationCreator {

    protected const tokenLength = 20;


    public static function create($userId)
    {

        Confirmations::insertNew($userId, self::generateToken());

    }


    protected static function generateToken()
    {
        $temporaryLogin = Utf8RandomString::get(self::tokenLength);

        while (Confirmations::getId($temporaryLogin))
        {
            $temporaryLogin = Utf8RandomString::get(self::tokenLength);
        }

        return $temporaryLogin;
    }

}

?>