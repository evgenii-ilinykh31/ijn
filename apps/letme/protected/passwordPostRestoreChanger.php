<?php


require_once 'protected/helpers/organizers/constants.php';
require_once 'protected/helpers/organizers/languages.php';
require_once 'protected/helpers/organizers/errors.php';
require_once 'protected/helpers/workers/languagesCode.php';
require_once 'protected/helpers/models/passwordRestorings.php';
require_once 'protected/helpers/models/users.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Organizers\Errors;
use Helpers\Workers\LanguagesCode;
use Helpers\Models\PasswordRestorings;
use Helpers\Models\Users;


class PasswordPostRestoreChanger {

    const thereIsNoEmail = [
        Languages::russian => [
            'Введенный email не соответствует тому на который была отправлена ссылка на восстановление пароля'
        ],
        Languages::english => [
            'Inserted email does not match one which password restoring link has been sent'
        ],
        Languages::spanish => [
            'El email insertado no coincide con uno cuyo enlace de restauración de contraseña se envió'
        ]
    ];

    const passwordSuccess = [
        Languages::russian => [
            'Пароль успешно сохранен'
        ],
        Languages::english => [
            'Password successfully changed'
        ],
        Languages::spanish => [
            'Contraseña cambiada correctamente'
        ]
    ];

    public function __construct()
    {

    }

    public function main(): void
    {
        //установить язык
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        //проверить token
        if ( ! array_key_exists(Constants::token, $_POST) || ! array_key_exists(Constants::email, $_POST) || ! array_key_exists(Constants::password, $_POST))
        {
            exit(json_encode([
                Constants::err,
                Errors::commonError[$languageCode]
            ]));
        }

        $userId = PasswordRestorings::getUserId($_POST[Constants::token]);

        if ( ! $userId)
        {
            exit(json_encode([
                Constants::err,
                Errors::commonError[$languageCode]
            ]));
        }

        //проверить email
        if (Users::getEmail($userId) !== strtolower($_POST[Constants::email]))
        {
            exit(json_encode([
                Constants::err,
                self::thereIsNoEmail[$languageCode][0]
            ]));
        }

        //записать новый пароль в БД
        Users::setNewPassword($userId, password_hash($_POST[Constants::password], PASSWORD_DEFAULT));

        //Удалить запись восстановления пароля из БД
        PasswordRestorings::delete($userId);

        //отправить сообщение, что все успешно
        print json_encode(
            ['success', self::passwordSuccess[$languageCode]]
        );


    }

}


$passwordRestoreChanger = new PasswordPostRestoreChanger();


$passwordRestoreChanger->main();