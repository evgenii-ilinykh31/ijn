<?php

require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/accountConfirmation.php';
require_once 'helpers/workers/languagesCode.php';

use Helpers\Models\Confirmations;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\AccountConfirmation;
use Helpers\Workers\LanguagesCode;

class RegistrationConfirmer {

    public static function main()
    {
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        if ( ! AccountConfirmation::couldConfirm($_GET[Constants::confirmRegistration]))
        {
            exit(
                Errors::noPage[$languageCode] . ' <a href="' . Constants::appLink . '">' . Constants::appLink . '</a>'
            );
        }

        AccountConfirmation::confirm($_GET[Constants::confirmRegistration]);

        header('Location: ' . Constants::appLink, true, 303);

        exit();
    }
}


RegistrationConfirmer::main();

?>