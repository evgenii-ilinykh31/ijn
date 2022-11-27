<?php

require_once 'protected/helpers/organizers/constants.php';
require_once 'protected/helpers/organizers/languages.php';
require_once 'protected/helpers/organizers/errors.php';
require_once 'protected/helpers/note.php';
require_once 'protected/helpers/sessionExtractor.php';
require_once 'protected/helpers/session.php';

use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Organizers\Errors;
use Helpers\Note;
use Helpers\SessionExtractor;
use Helpers\Session;


class NoteDeleter {


    public static function main()
    {
        $sessionId = SessionExtractor::getId();


        if ( ! $sessionId)
        {
            print Errors::commonError[Languages::russian];

            return;
        }


        $session = new Session($sessionId);


        if (
            ! array_key_exists(Constants::csrfToken, $_POST)
            || $_POST[Constants::csrfToken] !== $session->getTokenCsrf()
            || ! array_key_exists(Constants::id, $_POST)
        )
        {
            print Errors::commonError[Languages::russian];

            return;
        }





        if (Note::delete($_POST[Constants::id], $session->getUserId()))
        {
            print Constants::success;
        }
        else
        {
            print Errors::commonError[Languages::russian];
        }

    }
}


NoteDeleter::main();

?>