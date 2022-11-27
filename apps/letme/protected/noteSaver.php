<?php

require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/workers/languagesCode.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/userErrors.php';
require_once 'helpers/sessionExtractor.php';
require_once 'helpers/session.php';
require_once 'helpers/note.php';
require_once 'helpers/models/users.php';

use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;
use Helpers\Organizers\Errors;
use Helpers\Organizers\UserErrors;
use Helpers\SessionExtractor;
use Helpers\Session;
use Helpers\Note;
use Helpers\Models\Users;

class NoteSaver {

    public static function main()
    {
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        $sessionId = SessionExtractor::getId();

        if ( ! $sessionId)
        {
            print json_encode([
                Constants::err,
                Errors::commonError[$languageCode]
            ]);

            return;
        }


        $session = new Session($sessionId);

        $languageCode = Users::getLastLanguage($session->getUserId());

        if (
            ! array_key_exists(Constants::csrfToken, $_POST)
            || $_POST[Constants::csrfToken] !== $session->getTokenCsrf()
            || ! array_key_exists(Constants::text, $_POST)
        )
        {
            print Errors::commonError[$languageCode];

            return;
        }


        if ( ! Note::couldBeSaved($_POST[Constants::text]))
        {
            print json_encode([
                Constants::err,
                UserErrors::emptyNote[$languageCode]
            ]);

            return;
        }


        print json_encode([
            Constants::success,
            Note::saveGetId($session->getUserId(), $_POST[Constants::text]),
            Languages::noteDeleteTitleValues[$languageCode],
            Languages::noteEditTitleValues[$languageCode],
            Languages::noteToTopValues[$languageCode],
            Languages::noteToBottomValues[$languageCode],
            Languages::noteMoveValues[$languageCode]
        ]);

    }
}


NoteSaver::main();

?>