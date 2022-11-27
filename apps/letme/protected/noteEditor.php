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
require_once 'protected/helpers/organizers/languages.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\UserErrors;
use Helpers\Note;
use Helpers\SessionExtractor;
use Helpers\Session;
use Helpers\Models\Notes;
use Helpers\Models\Users;
use Helpers\Workers\LanguagesCode;
use Helpers\Organizers\Languages;


class NoteEditor {

    const noteChanged = [
        Languages::russian => [
            'Изменения успешно сохранены'
        ],
        Languages::english => [
            'Changes successfully changed'
        ],
        Languages::spanish => [
            'Los cambios se modificaron correctamente'
        ]
    ];

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


        if ( ! array_key_exists(Constants::text, $_POST))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::commonError[$languageCode] . 'нет ключа noteText'
                ]
            );

            return;
        }


        $noteId = $_POST[Constants::id];

        $noteText = $_POST[Constants::text];

        //проверка прав доступа
        if (intval(Notes::getUserId($noteId)) !== intval($userId))
        {
            print json_encode(
                [
                    Constants::err,
                    Errors::noPermission[$languageCode]
                ]
            );

            return;
        }

        //проверка на соответствие текста заметки требованиям
        if ( ! Note::couldBeSaved($noteText))
        {
            print json_encode(
                [
                    Constants::err,
                    UserErrors::emptyNote[$languageCode]
                ]
            );

            return;
        }


        Notes::edit($noteText, intval($noteId));

        print json_encode(
            [
                Constants::success,
                self::noteChanged[$languageCode][0]
            ]
        );



    }

}


$noteEditor = new NoteEditor();

$noteEditor->main();


