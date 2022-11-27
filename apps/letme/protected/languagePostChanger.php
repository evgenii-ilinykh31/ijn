<?php

require_once 'helpers/workers/languagesCode.php';
require_once 'helpers/html/findnoteView.php';
require_once 'helpers/html/takenoteView.php';
require_once 'helpers/html/controlView.php';
require_once 'helpers/html/settingsView.php';
require_once 'helpers/html/flowLoginView.php';
require_once 'helpers/html/popupChangepasswordView.php';
require_once 'helpers/html/restorePasswordView.php';
require_once 'helpers/sessionExtractor.php';
require_once 'helpers/session.php';
require_once 'helpers/models/users.php';
require_once 'helpers/organizers/constants.php';
require_once 'helpers/organizers/errors.php';
require_once 'helpers/organizers/languages.php';
require_once 'helpers/html/popupEditNoteView.php';
require_once 'helpers/html/undoDeleteView.php';


use Helpers\Organizers\Constants;
use Helpers\Organizers\Errors;
use Helpers\Organizers\Languages;
use Helpers\Workers\LanguagesCode;
use Helpers\SessionExtractor;
use Helpers\Session;
use Helpers\Models\Users;
use Helpers\Html\
{
    FindnoteView,
    TakenoteView,
    ControlView,
    SettingsView,
    FlowLoginView,
    PopupChangepasswordView,
    RestorePasswordView,
    PopupEditNoteView,
    UndoDeleteView
};


class LanguagePostChanger {


    public function _construct()
    {

    }

    public function main(): void
    {

        //returns json array();

        $code = LanguagesCode::getDefaultValue();

        if (LanguagesCode::isLanguageCodeInPost())
        {
            $code = LanguagesCode::getLanguageCodePost();
        }

        LanguagesCode::setLanguageCodeCookie($code);

        $this->setLastLanguage($code);

        $response = json_encode(
            [
                FlowLoginView::getNode($code),
                SettingsView::getNode($code),
                ControlView::getNode($code),
                TakenoteView::getNode($code),
                FindnoteView::getNode($code),
                PopupChangepasswordView::getNode($code),
                RestorePasswordView::getNode($code),
                PopupEditNoteView::getNode($code),
                Languages::noteDeleteTitleValues[$code],
                Languages::noteEditTitleValues[$code],
                Languages::settingsTitleValues[$code],
                UndoDeleteView::getNode($code),
                Languages::exampleImages[$code],
                Languages::noteToTopValues[$code],
                Languages::noteToBottomValues[$code],
                Languages::noteMoveValues[$code]
            ]
        );

        print $response;

    }

    protected function setLastLanguage(string $languageCode): void
    {

        //обработка запроса в случае, если установлены cookie, соотетствующие сессии
        $sessionId = SessionExtractor::getId();

        if ( ! $sessionId)
        {
            return;
        }

        $session = new Session($sessionId);


        if ( ! array_key_exists(Constants::csrfToken, $_POST) || $_POST[Constants::csrfToken] !== $session->getTokenCsrf())
        {
            print Errors::commonError[Languages::russian];

            return;
        }


        $userId = $session->getUserId();

        Users::setLastLanguage($userId, $languageCode);

    }

}


$languageChanger = new LanguagePostChanger();

$languageChanger->main();