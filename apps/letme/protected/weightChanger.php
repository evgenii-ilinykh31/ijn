<?php


require_once 'protected/helpers/models/users.php';
require_once 'protected/helpers/organizers/constants.php';
require_once 'protected/helpers/organizers/errors.php';
require_once 'protected/helpers/session.php';
require_once 'protected/helpers/sessionExtractor.php';
require_once 'protected/helpers/workers/languagesCode.php';
require_once 'protected/helpers/models/notes.php';



use Helpers\Models\Users;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Session;
use Helpers\SessionExtractor;
use Helpers\Workers\LanguagesCode;
use Helpers\Models\Notes;


class WeightChanger {

    //wait for POST[csrfToken, noteMoveId, noteMoveBeforeId]


    public function main(): void
    {
        //Проверить автризацию
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        $sessionId = SessionExtractor::getId();

        if ( ! $sessionId)
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode]
                ]
            );

            return;
        }


        $session = new Session($sessionId);

        $userId = $session->getUserId();

        $languageCode = Users::getLastLanguage($userId);


        if ( ! array_key_exists(Constants::csrfToken, $_POST))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . ' нет ключа csrf'
                ]
            );

            return;
        }


        if ($_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . ' csrf не соответствует'
                ]
            );

            return;
        }

        //Проверить наличие необходимых ключей
        if ( ! array_key_exists(Constants::noteMoveId, $_POST))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . ' нет ключа noteId'
                ]
            );

            return;
        }

        $noteMoveId = intval($_POST[Constants::noteMoveId]);


        $noteMoveBeforeId = 0;

        if (array_key_exists(Constants::noteMoveBeforeId, $_POST))
        {
            $noteMoveBeforeId = intval($_POST[Constants::noteMoveBeforeId]);
        }


        //Проверить право работы с данными noteMoveId

        $userIdByNoteId = Notes::getUserId($noteMoveId);

        if ( ! $userIdByNoteId)
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . ' попытка переместить заметку с не существующим noteMoveId'
                ]
            );
        }

        if (intval($userIdByNoteId) !== intval($userId))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::noPermission[$languageCode]
                ]
            );

            return;
        }

        //Проверить право работы с данными noteMoveBeforeId, если оно установлено

        if ($noteMoveBeforeId)
        {
            $userIdByNoteId = Notes::getUserId($noteMoveBeforeId);

            if ( ! $userIdByNoteId)
            {
                print json_encode(
                    [
                        Constants::err,
                        Errors::commonError[$languageCode] . ' попытка переместить заметку с не существующим noteMoveBeforeId'
                    ]
                );
            }

            if (intval($userIdByNoteId) !== intval($userId))
            {
                print json_encode(
                    [
                        Constants::err,
                        Errors::noPermission[$languageCode]
                    ]
                );

                return;
            }
        }

        //Занести веса в БД
        Notes::changeWeight($noteMoveId, $noteMoveBeforeId);

        print json_encode(
            [
                Constants::success
            ]
        );
    }
}


$weightChanger = new WeightChanger();

$weightChanger->main();