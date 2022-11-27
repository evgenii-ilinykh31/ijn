<?php


require_once 'helpers/password.php';
require_once 'helpers/email.php';
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/confirmNote.php';
require_once 'helpers/sessionCreator.php';
require_once 'helpers/userCreator.php';
require_once 'helpers/accountConfirmationCreator.php';
require_once 'helpers/workers/languagesCode.php';


use Helpers\Password;
use Helpers\Email;
use Helpers\UserCreator;
use Helpers\AccountConfirmationCreator;
use Helpers\SessionCreator;
use Helpers\ConfirmNote;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;


class Registration {

    protected const enterLoginPassword = [
        Languages::russian => 'Введите email и пароль для регистрации',
        Languages::english => 'Enter email and password to register',
        Languages::spanish => 'Ingrese email y contraseña'
    ];

    public static function main()
    {

        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();


        //check if email and password keys exist in Post
        if (
            ! array_key_exists(Constants::email, $_POST) || ! array_key_exists(Constants::password, $_POST)
        )
        {
            print json_encode([
                Constants::err,
                Errors::commonError[$languageCode]
            ]);

            return;
        }


        //check if user inserted email or password
        if ( ! $_POST[Constants::email] && ! $_POST[Constants::password])
        {
            print json_encode([
                Constants::err,
                self::enterLoginPassword[$languageCode]
            ]);

            return;
        }

        //check password
        if ( ! Password::isSafe($_POST[Constants::password]))
        {
            print json_encode(
                [
                    Constants::err,
                    Password::getSafetyError($languageCode)
                ]
            );

            return;
        }


        //check email
        if ( ! Email::isValid($_POST[Constants::email]))
        {
            print json_encode(
                [
                    Constants::err,
                    Email::getValidError($languageCode)
                ]
            );

            return;
        }


        if (Email::isExist($_POST[Constants::email]))
        {
            print json_encode(
                [
                    Constants::err,
                    Email::getExistError($languageCode)
                ]
            );

            return;
        }


        //create new user

        if (LanguagesCode::isLanguageCodeInCookie())
        {
            $languageCode = LanguagesCode::getLanguageCodeCookie();
        }

        $user = new UserCreator($_POST[Constants::email], $_POST[Constants::password], $languageCode);


        //create session
        $session = new SessionCreator($user->getId());


        //create confirmation
        AccountConfirmationCreator::create($user->getId());


        //print response
        echo json_encode([
            $session->getTokenCsrf(),
            ConfirmNote::createGetId($user->getId(), $languageCode),
            ConfirmNote::text[$languageCode],
            Languages::noteDeleteTitleValues[$languageCode],
            Languages::noteEditTitleValues[$languageCode],
            Languages::settingsTitleValues[$languageCode],
            Languages::noteToTopValues[$languageCode],
            Languages::noteToBottomValues[$languageCode],
            Languages::noteMoveValues[$languageCode]
        ]);

    }


}


Registration::main();


?>