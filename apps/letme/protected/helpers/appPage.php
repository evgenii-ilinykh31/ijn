<?php

namespace Helpers;


require_once "organizers/paths.php";
require_once "organizers/constants.php";
require_once "organizers/languages.php";
require_once 'sessionExtractor.php';
require_once 'session.php';
require_once "models/sessions.php";
require_once "models/users.php";
require_once 'workers/languagesCode.php';
require_once 'html/findnoteView.php';
require_once 'html/takenoteView.php';
require_once 'html/controlView.php';
require_once 'html/settingsView.php';
require_once 'html/flowLoginView.php';
require_once 'html/authorizeLanguageView.php';
require_once 'html/popupLanguageView.php';
require_once 'html/popupChangepasswordView.php';
require_once 'html/restorePasswordView.php';
require_once 'html/popupEditNoteView.php';
require_once 'html/undoDeleteView.php';


use Helpers\Html\AuthorizeLanguageView;
use Helpers\Organizers\Paths;
use Helpers\Organizers\Constants;
use Helpers\Organizers\Languages;
use Helpers\Models\Sessions;
use Helpers\Models\Users;
use Helpers\Models\Notes;
use Helpers\Html\FindnoteView;
use Helpers\Html\TakenoteView;
use Helpers\Html\ControlView;
use Helpers\Html\SettingsView;
use Helpers\Html\FlowLoginView;
use Helpers\Workers\LanguagesCode;
use Helpers\Html\PopupLanguageView;
use Helpers\Html\PopupChangepasswordView;
use Helpers\Html\RestorePasswordView;
use Helpers\Html\PopupEditNoteView;
use Helpers\Html\UndoDeleteView;


class AppPage {

    protected const settingsTitle = 'settingsTitle';
    protected const noteDeleteTitle = 'deleteTitle';
    protected const noteEditTitle = 'editTitle';
    protected const toTopTitle = 'toTopTitle';
    protected const toBottomTitle = 'toBottomTitle';
    protected const moveTitle = 'moveTitle';


    public static function getLoginMode(): string
    {
        $appContents = file_get_contents(Paths::appHtml);

        $languageCode = self::getAndSetLanguageCode();

        return str_replace(
            [
                '{{' . self::settingsTitle . '}}',
                '<!--{{controlView}}-->',
                '<!--{{findnoteView}}-->',
                '<!--{{flowloginView}}-->',
                '<!--{{settingsView}}-->',
                '<!--{{takenoteView}}-->',
                '<!--{{authorizeLanguageView}}-->',
                '<!--{{popupLanguageView}}-->',
                '<!--{{changepasswordView}}-->',
                '<!--{{restorePasswordView}}-->',
                '<!--{{editnoteView}}-->',
                '<!--{{undoDelete}}-->',
                '<!--{{exampleImage}}-->',
                '<!--{{bodyStyle}}-->',
                '<!--{{appStyle}}-->'

            ],
            [
                Languages::settingsTitleValues[$languageCode],
                ControlView::getNode($languageCode),
                FindnoteView::getNode($languageCode),
                FlowLoginView::getNode($languageCode),
                SettingsView::getNode($languageCode),
                TakenoteView::getNode($languageCode),
                AuthorizeLanguageView::getNode($languageCode),
                PopupLanguageView::getNode($languageCode),
                PopupChangepasswordView::getNode($languageCode),
                RestorePasswordView::getNode($languageCode),
                PopupEditNoteView::getNode($languageCode),
                UndoDeleteView::getNode($languageCode),
                Languages::exampleImages[$languageCode],
                'overflow: hidden;',
                ''
            ],
            $appContents
        );
    }

    public static function getJobMode($sessionId): string
    {
        $appContents = file_get_contents(Paths::appHtml);

        $appContents = preg_replace('/(login)(\W)/', 'job$2', $appContents);

        $languageCode = self::getAndSetLanguageCode();

        return str_replace(
            [
                '<!--{{' . Constants::csrfToken . '}}-->',
                '<!--{{' . Constants::notes . '}}-->',
                '{{' . self::settingsTitle . '}}',
                '<!--{{controlView}}-->',
                '<!--{{findnoteView}}-->',
                '<!--{{flowloginView}}-->',
                '<!--{{settingsView}}-->',
                '<!--{{takenoteView}}-->',
                '<!--{{authorizeLanguageView}}-->',
                '<!--{{popupLanguageView}}-->',
                '<!--{{changepasswordView}}-->',
                '<!--{{restorePasswordView}}-->',
                '<!--{{editnoteView}}-->',
                '<!--{{undoDelete}}-->',
                "<!--{{exampleImage}}-->",
                '<!--{{bodyStyle}}-->',
                '<!--{{appStyle}}-->'
            ],
            [
                Sessions::getTokenCsrf($sessionId),
                self::getNotesBlock(intval(Sessions::getUserId($sessionId)), $languageCode),
                Languages::settingsTitleValues[$languageCode],
                ControlView::getNode($languageCode),
                FindnoteView::getNode($languageCode),
                FlowLoginView::getNode($languageCode),
                SettingsView::getNode($languageCode),
                TakenoteView::getNode($languageCode),
                AuthorizeLanguageView::getNode($languageCode),
                PopupLanguageView::getNode($languageCode),
                PopupChangepasswordView::getNode($languageCode),
                RestorePasswordView::getNode($languageCode),
                PopupEditNoteView::getNode($languageCode),
                UndoDeleteView::getNode($languageCode),
                Languages::exampleImages[$languageCode],
                'position: fixed; height: 100%;',
                'height: fit-content;'
            ],
            $appContents
        );

    }

    protected static function getNotesBlock(int $userId, $language)
    {
        require_once 'models/notes.php';

        $noteTemplate = file_get_contents(Paths::noteHtml);

        $notes = Notes::getList($userId, Constants::notesNumberPerRequest);

        for ($i = 0; $i < count($notes); $i++)
        {
            $notes[$i] = str_replace(
                [
                    '{{' . Constants::id . '}}',
                    '<!--{{' . Constants::note . '}}-->',
                    '{{' . self::noteDeleteTitle . '}}',
                    '{{' . self::noteEditTitle . '}}',
                    '{{' . self::toTopTitle . '}}',
                    '{{' . self::toBottomTitle . '}}',
                    '{{' . self::moveTitle . '}}'
                ],
                [
                    $notes[$i][0],
                    $notes[$i][1],
                    Languages::noteDeleteTitleValues[$language],
                    Languages::noteEditTitleValues[$language],
                    Languages::noteToTopValues[$language],
                    Languages::noteToBottomValues[$language],
                    Languages::noteMoveValues[$language]
                ],
                $noteTemplate
            );
        }

        return implode("\r", $notes);
    }


    protected static function getAndSetLanguageCode(): string
    {
        $sessionId = SessionExtractor::getId();

        if ($sessionId)
        {
            $session = new Session($sessionId);

            $userId = $session->getUserId();

            $code = Users::getLastLanguage($userId);

            $code = LanguagesCode::checkAndGetLanguageCode($code);

            LanguagesCode::setLanguageCodeCookie($code);

            return $code;
        }

        if (LanguagesCode::isLanguageCodeInCookie())
        {
            return LanguagesCode::getLanguageCodeCookie();
        }

        return LanguagesCode::getLanguageCodeBrowserDefault();
    }

}

?>