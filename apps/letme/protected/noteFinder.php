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

class NoteFinder {

//    const zeroError = [
//        Languages::russian => 'Пустая строка запроса',
//        Languages::english => 'Empty request row'
//    ];

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


        if (strlen($_POST[Constants::text]) == 0)
        {
            print json_encode([
                Constants::success,
                Note::getList($session->getUserId())
            ]);

            return;
        }


//        if ( ! Note::isCanFind($_POST[Constants::text]))
//        {
//            print json_encode(Constants::err, self::zeroError[Languages::russian]);
//
//            return;
//        }


        print json_encode([
            Constants::success,
            Note::find($session->getUserId(), $_POST[Constants::text])
        ]);

    }

}


NoteFinder::main();

?>