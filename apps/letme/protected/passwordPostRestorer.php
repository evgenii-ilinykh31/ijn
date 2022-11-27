<?php


require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/workers/languagesCode.php';
require_once 'helpers/models/users.php';
require_once 'helpers/models/passwordRestorings.php';
require_once 'helpers/workers/utf8RandomString.php';


use Helpers\Models\Confirmations;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;
use Helpers\Models\Users;
use Helpers\Models\PasswordRestorings;
use Helpers\Workers\Utf8RandomString;


class PasswordPostRestorer {

    const tokenLength = 25;

    protected $noEmail = [
        Languages::russian => ['Email не зарегистрирован'],
        Languages::english => ['Email is not registered'],
        Languages::spanish => ['Email no está registrado']
    ];


    protected $emailHasBeenSent = [
        Languages::russian => ['Ссылка на восстановление пароля отправлена на указанный email'],
        Languages::english => ['Link to password restoring has been sent to specified email'],
        Languages::spanish => ['Se envió un enlace para restaurar la contraseña al correo email']
    ];


    public function __construct()
    {
    }

    public function main(): void
    {
        //>>>cron script is using because of long email execution

        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        //check if emailKey is exist in Post
        if ( ! array_key_exists(Constants::email, $_POST))
        {
            exit(json_encode([Constants::err, Errors::commonError[$languageCode]]));
        }

        $email = $_POST[Constants::email];

        //get userId
        $userId = Users::getId($email);

        //check if email is exist
        if ( ! $userId)
        {
            exit(json_encode([Constants::err, $this->noEmail[$languageCode][0]]));
        }

        //generateToken
        $passwordRestoreToken = $this->generateRestoreToken();

        //delete existing userId if they are
        PasswordRestorings::delete($userId);

        //create new record in password restoring table
        PasswordRestorings::insertNew(intval($userId), $passwordRestoreToken);

        //send success response
        print json_encode($this->emailHasBeenSent[$languageCode][0]);
    }


    protected function generateRestoreToken(): string
    {
        $temporaryLogin = Utf8RandomString::get(self::tokenLength);

        while (Confirmations::getId($temporaryLogin))
        {
            $temporaryLogin = Utf8RandomString::get(self::tokenLength);
        }

        return $temporaryLogin;
    }
}


$passwordRestorer = new PasswordPostRestorer();

$passwordRestorer->main();