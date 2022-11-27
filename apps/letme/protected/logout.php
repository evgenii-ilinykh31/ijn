<?php

require_once 'helpers/sessionExtractor.php';
require_once 'helpers/session.php';
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/organizers/errors.php';

use Helpers\SessionExtractor;
use Helpers\Session;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Organizers\Errors;

class Logout {

    public static function main()
    {
        $id = SessionExtractor::getId();

        if ( ! $id)
        {
            print Errors::commonError[Languages::russian];

            return;
        }

        $session = new Session($id);

        if ( ! array_key_exists(Constants::csrfToken, $_POST) || $_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            print Errors::commonError[Languages::russian];

            return;
        }

        $session->unset();

        print 200;


    }

}


Logout::main();

?>