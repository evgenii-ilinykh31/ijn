<?php

require_once '/home/ijn/apps/letme/protected/helpers/models/confirmations.php';
require_once '/home/ijn/apps/letme/protected/helpers/models/users.php';
require_once '/home/ijn/apps/letme/protected/helpers/email/email.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/languages.php';
require_once '/home/ijn/apps/letme/protected/helpers/organizers/paths.php';
require_once '/home/ijn/apps/letme/protected/helpers/html/confirmationEmailView.php';


use Helpers\Models\Confirmations;
use Helpers\Models\Users;
use Helpers\Email\Email;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Organizers\Paths;
use Helpers\Html\ConfirmationEmailView;


require_once 'logWriter.php';

use Helpers\LogWriter;

class Cron_ConfirmationSender {

    const subject = [
        Languages::english => 'Confirm account',
        Languages::russian => 'Подтверждение аккаунта',
        Languages::spanish => 'Confirmar cuenta'
    ];

    public static function sendAllUnsent()
    {

        try
        {
            $confirmationEmailView = new ConfirmationEmailView();

            $notSentList = Confirmations::getAllUnsentUserId();

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
                    $confirmationEmailView->getEmail($languageCode, Constants::appLink . '?' . Constants::confirmRegistration . '=' . Confirmations::getToken($userId))
                );

                Confirmations::setSent($userId);
            }
        } catch (Exception $error)
        {
            LogWriter::write(__FILE__, 'sendAllUnsent', $error->getMessage());
        }


//        LogWriter::write(__FILE__);

    }

}

Cron_ConfirmationSender::sendAllUnsent();

?>
