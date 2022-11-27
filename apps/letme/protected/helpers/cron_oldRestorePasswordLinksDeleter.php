<?php


namespace Helpers;


ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '/home/ijn/apps/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/apps/letme/protected/helpers/models/passwordRestorings.php';


use Helpers\Organizers\Constants;
use Helpers\Models\PasswordRestorings;


class Cron_oldRestorePasswordLinksDeleter {

    const oldLinkDeleteLimit = 900;

    public static function main()
    {
        PasswordRestorings::deleteOld(self::oldLinkDeleteLimit);
    }
}


Cron_oldRestorePasswordLinksDeleter::main();