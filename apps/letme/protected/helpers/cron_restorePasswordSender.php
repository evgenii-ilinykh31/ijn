<?php

ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '/home/ijn/apps/letme/protected/helpers/models/passwordRestorings.php';
require_once '/home/ijn/apps/letme/protected/helpers/models/users.php';
require_once '/home/ijn/apps/letme/protected/helpers/email/email.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/languages.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/paths.php';
require_once '/home/ijn/apps/letme/protected/helpers/html/restorePasswordEmailView.php';


use Helpers\Email\Email;
use Helpers\Models\PasswordRestorings;
use Helpers\Models\Users;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Html\RestorePasswordEmailView;


require_once 'logWriter.php';

use Helpers\LogWriter;



class Cron_restorePasswordSender {

    const subject = [
        Languages::russian => 'Восстановление пароля',
        Languages::english => 'Restore password',
        Languages::spanish => 'Restaurar contraseña'
    ];


    public static function sendAllUnsent()
    {

        try
        {
            $notSentList = PasswordRestorings::getAllUnsentUserId();

            if (count($notSentList) == 0)
            {
                return;
            }

            foreach ($notSentList as $userId)
            {
                $languageCode = Users::getLastLanguage($userId);

                Email::send
                (
                    Users::getEmail($userId),
                    Constants::appName . '. ' . self::subject[$languageCode] . '.',
                    RestorePasswordEmailView::getEmail($languageCode, Constants::appLink . '?' . Constants::passwordRestore . '=' . PasswordRestorings::getToken($userId))
                );

                PasswordRestorings::setSent($userId);
            }
        } catch (Exception $error)
        {
            LogWriter::write(__FILE__, 'restorePasswordSender', $error->getMessage());
        }

    }

}


Cron_restorePasswordSender::sendAllUnsent();