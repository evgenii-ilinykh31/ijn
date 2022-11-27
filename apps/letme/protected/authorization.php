<?php

require_once 'helpers/login.php';
require_once 'helpers/sessionCreator.php';
require_once 'helpers/note.php';
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/languages.php';

//заплатки:
require_once 'helpers/email.php';
require_once 'helpers/password.php';
require_once 'helpers/models/users.php';
require_once 'helpers/html/flowLoginView.php';
require_once 'helpers/html/settingsView.php';
require_once 'helpers/html/controlView.php';
require_once 'helpers/html/takenoteView.php';
require_once 'helpers/html/findnoteView.php';
require_once 'helpers/html/restorePasswordView.php';
require_once 'helpers/html/popupEditNoteView.php';
require_once 'helpers/html/undoDeleteView.php';
require_once 'helpers/workers/languagesCode.php';


use Helpers\Email;
use Helpers\Login;
use Helpers\Password;
use Helpers\SessionCreator;
use Helpers\Note;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;

//заплатки
use Helpers\Models\Users;
use Helpers\Html\FlowLoginView;
use Helpers\Html\SettingsView;
use Helpers\Html\ControlView;
use Helpers\Html\TakenoteView;
use Helpers\Html\FindnoteView;
use Helpers\Html\RestorePasswordView;
use Helpers\Html\PopupEditNoteView;
use Helpers\Html\UndoDeleteView;



class Authorization {

    public static function main()
    {
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();

        //check email
        if ( ! Email::isValid($_POST[Constants::email]))
        {
            print json_encode(
                [
                    Constants::err,
                    Email::getValidError($languageCode)
                ]
            );

            return;
        }

        //check password
        if ( ! Password::isSafe($_POST[Constants::password]))
        {
            print json_encode(
                [
                    Constants::err,
                    Password::getSafetyError($languageCode)
                ]
            );

            return;
        }

        //password is checking due login
        $login = new Login($_POST[Constants::email], $_POST[Constants::password]);

        if ( ! $login->isLoginSuccessful())
        {
            print json_encode(
                [
                    Constants::err,
                    $login->getLoginError($languageCode)
                ]
            );

            return;
        }

        //create session
        $session = new SessionCreator($login->getUserId());

        // send: csrf-token and array of notes
        $languageCode = Users::getLastLanguage($login->getUserId());

        print json_encode(
            [
                $session->getTokenCsrf(),
                Note::getList($login->getUserId()),
                FlowLoginView::getNode($languageCode),
                SettingsView::getNode($languageCode),
                ControlView::getNode($languageCode),
                TakenoteView::getNode($languageCode),
                FindnoteView::getNode($languageCode),
                RestorePasswordView::getNode($languageCode),
                PopupEditNoteView::getNode($languageCode),
                $languageCode,
                Languages::noteDeleteTitleValues[$languageCode],
                Languages::noteEditTitleValues[$languageCode],
                Languages::settingsTitleValues[$languageCode],
                UndoDeleteView::getNode($languageCode),
                Languages::exampleImages[$languageCode],
                Languages::noteToTopValues[$languageCode],
                Languages::noteToBottomValues[$languageCode],
                Languages::noteMoveValues[$languageCode]
            ]
        );

    }

}

Authorization::main();


?>