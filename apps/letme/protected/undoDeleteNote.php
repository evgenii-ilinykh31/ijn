<?php


require_once 'protected/helpers/organizers/constants.php';
require_once 'protected/helpers/organizers/errors.php';
require_once 'helpers/organizers/userErrors.php';
require_once 'protected/helpers/note.php';
require_once 'protected/helpers/sessionExtractor.php';
require_once 'protected/helpers/session.php';
require_once 'protected/helpers/models/notes.php';
require_once 'protected/helpers/models/users.php';
require_once 'protected/helpers/workers/languagesCode.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\UserErrors;
use Helpers\Note;
use Helpers\SessionExtractor;
use Helpers\Session;
use Helpers\Models\Notes;
use Helpers\Models\Users;
use Helpers\Workers\LanguagesCode;


class UndoDeleteNote {

    public function main()
    {
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
                    Errors::commonError[$languageCode] . 'нет ключа csrf'
                ]
            );

            return;
        }


        if ($_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . 'csrf не соответствует'
                ]
            );

            return;
        }


        if ( ! array_key_exists(Constants::id, $_POST))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . 'нет ключа noteId'
                ]
            );

            return;
        }


        $noteId = $_POST[Constants::id];

        $userIdFromNote = Notes::getUserId($noteId);

        //проверка, что note с таким id присутствует в БД
        if ( ! $userIdFromNote)
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . 'попытка восстановить заметку с не существующим id'
                ]
            );

            return;
        }

        //проверка, что userId имеет право на отмену удаления noteId
        if (intval($userId) !== intval($userIdFromNote))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::noPermission[$languageCode]
                ]
            );

            return;
        }

        //восстановление удаленной заметки
        Notes::undoDelete(intval($noteId));

        print json_encode(
            [
                Constants::success,
                Notes::getIdOfBeforeWeightById(intval($userIdFromNote), intval($noteId))
            ]
        );



    }

}




$noteEditor = new UndoDeleteNote();

$noteEditor->main();