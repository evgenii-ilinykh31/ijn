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


class NoteGetter {

    public static function main()
    {
        $sessionId = SessionExtractor::getId();


        if ( ! $sessionId)
        {
            print json_encode(Constants::err, Errors::commonError[Languages::russian]);

            return;
        }


        $session = new Session($sessionId);


        if ( ! array_key_exists(Constants::csrfToken, $_POST) || $_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            print Errors::commonError[Languages::russian];

            return;
        }


        print json_encode([
            Constants::success,
            Note::getList($session->getUserId(), $_POST[Constants::text])
        ]);

    }
}


NoteGetter::main();

?>
