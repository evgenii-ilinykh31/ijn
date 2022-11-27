<?php

namespace Helpers;


require_once 'organizers/languages.php';
require_once 'models/confirmations.php';
require_once 'models/notes.php';
require_once 'models/users.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Models\Confirmations;
use Helpers\Models\Notes;
use Helpers\Models\Users;


class AccountConfirmation {

    protected const yourRegistrationConfirmed = [
        Languages::russian => 'Ваша регистрация в проекте LetMe успешно подтверждена',
        Languages::english => 'You LetMe project registration successfully confirmed',
        Languages::spanish => 'El registro del proyecto de LetMe se ha confirmado con éxito'
    ];

    protected function __construct()
    {

    }

    public static function couldConfirm(string $token): bool
    {
        if ( ! Confirmations::getUserId($token))
        {
            return false;
        }

        return true;
    }

    public static function confirm(string $token): void
    {

        $userId = Confirmations::getUserId($token);

        $userLanguageCode = Users::getLastLanguage($userId);

        Confirmations::deleteConfirmed($token);

        Notes::insertNewGetId($userId, self::yourRegistrationConfirmed[$userLanguageCode]);

    }

}

?>