<?php

require_once 'helpers/models/users.php';
require_once 'helpers/models/passwordRestorings.php';
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/workers/languagesCode.php';
require_once 'helpers/html/passwordGetRestorerView.php';


use Helpers\Models\Users;
use Helpers\Models\PasswordRestorings;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;
use Helpers\Html\PasswordGetRestorerView;


class PasswordGetRestorer {

    public function __construct()
    {

    }

    public function main(): void
    {

        //set language
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        //set token
        $token = $_GET[Constants::passwordRestore];

        //check if token is exist in passwordRestorings table
        if ( ! PasswordRestorings::getId($token))
        {
            exit(
                Errors::noPage[$languageCode] . ' <a href="' . Constants::appLink . '">' . Constants::appLink . '</a>'
            );
        }

        $userId = PasswordRestorings::getUserId($token);

        $languageCode = Users::getLastLanguage($userId);

        LanguagesCode::setLanguageCodeCookie($languageCode);

        //send passwordGetRestoringView
        print PasswordGetRestorerView::getNode($languageCode);

    }
}


$passwordRestorer = new PasswordGetRestorer();

$passwordRestorer->main();