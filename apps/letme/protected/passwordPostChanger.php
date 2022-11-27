<?php
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/session.php';
require_once 'helpers/sessionExtractor.php';
require_once 'helpers/password.php';
require_once 'helpers/workers/languagesCode.php';
require_once 'helpers/organizers/languages.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\Session;
use Helpers\SessionExtractor;
use Helpers\Password;
use Helpers\Workers\LanguagesCode;



class PasswordPostChanger {

    const oldpassword = 'oldpassword';
    const newpassword = 'newpassword';

    protected $passwordUnmatchError = [
        Languages::russian => ['Старый пароль не совпадает'],
        Languages::english => ['Old password does not match'],
        Languages::spanish => ['La contraseña anterior no coincide']
    ];

    protected $passwordChangedSuccess = [
        Languages::russian => ['Пароль успешно изменен'],
        Languages::english => ['Password successfully changed'],
        Languages::spanish => ['Contraseña cambiada correctamente']
    ];

    public function __construct()
    {

    }

    public function main(): void
    {
        //get language
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        //get session id
        $sessionId = SessionExtractor::getId();

        if ( ! $sessionId)
        {
            exit(json_encode(Constants::err, Errors::commonError[$languageCode]));
        }

        //create session
        $session = new Session($sessionId);

        //check csrf
        if ( ! array_key_exists(Constants::csrfToken, $_POST) || $_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            exit(
            json_encode([Constants::err, Errors::commonError[$languageCode]]));
        }

        //check password exists
        if ( ! array_key_exists(self::oldpassword, $_POST) || ! array_key_exists(self::newpassword, $_POST))
        {
            exit(json_encode([Constants::err, Errors::commonError[$languageCode]]));
        }

        //check password safity
        if ( ! Password::isSafe($_POST[self::oldpassword]) || ! Password::isSafe(self::newpassword))
        {
            exit(json_encode([Constants::err, Password::getSafetyError($languageCode)]));
        }

        //check old password matches
        if ( ! Password::isPasswordMatches($_POST[self::oldpassword], $session->getUserId()))
        {
            exit(json_encode([Constants::err, $this->passwordUnmatchError[$languageCode][0]]));
        }

        //set new password
        Password::setNewPassword($session->getUserId(), $_POST[self::newpassword]);

        print json_encode($this->passwordChangedSuccess[$languageCode][0]);

    }
}


$passwordChanger = new PasswordPostChanger();


$passwordChanger->main();