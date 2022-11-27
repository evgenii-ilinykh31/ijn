<?php
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT"); // Anytime in the past

require_once 'protected/helpers/organizers/constants.php';

use Helpers\Organizers\Constants;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

    if ($_POST[Constants::status] === 'registration')
    {
        require_once 'protected/registration.php';
    }
    elseif ($_POST[Constants::status] === 'authorization')
    {
        require_once 'protected/authorization.php';
    }
    elseif ($_POST[Constants::status] === 'logout')
    {
        require_once 'protected/logout.php';
    }
    elseif ($_POST[Constants::status] === 'getNotes')
    {
        require_once 'protected/noteGetter.php';
    }
    elseif ($_POST[Constants::status] === 'findNotes')
    {
        require_once 'protected/noteFinder.php';
    }
    elseif ($_POST[Constants::status] === 'takeNote')
    {
        //expects $_POST['status' => 'takeNote', 'text' => value]
        require_once 'protected/noteSaver.php';
    }
    elseif ($_POST[Constants::status] === 'editNote')
    {
        //expects $_POST['status' => 'takeNote', 'csrfToken' => value, 'id' => value, 'text' => value]
        require_once 'protected/noteEditor.php';
    }
    elseif ($_POST[Constants::status] === 'deleteNote')
    {
        //expects $_POST['status' => 'deleteNote', 'id' => value]
        require_once 'protected/noteDeleter.php';
    }
    elseif ($_POST[Constants::status] === 'undoDeleteNode')
    {
        require_once 'protected/undoDeleteNote.php';
    }
    elseif ($_POST[Constants::status] === 'changeWeight')
    {
        require_once 'protected/weightChanger.php';
    }
    elseif ($_POST[Constants::status] === 'changeLanguage')
    {
        //expects $_POST['status' => changeLanguage, 'languagecode' => 'ru/en/es']
        //поскольку два представления смешаны - можно запрашивать и отправлять сразу оба
        //print json array:
        //[flowLoginView, settingsView, controlView, takenoteView, findnoteView]
        require_once 'protected/languagePostChanger.php';
    }
    elseif ($_POST[Constants::status] === 'changepassword')
    {
        require_once 'protected/passwordPostChanger.php';
    }
    elseif ($_POST[Constants::status] === 'restorePassword')
    {
        require_once 'protected/passwordPostRestorer.php';
    }
    else if ($_POST[Constants::status] === 'passwordPostRestoreChanger')
    {
        require_once 'protected/passwordPostRestoreChanger.php';
    }
    else
    {
        require_once 'protected/errorHandler.php';
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET')
{

    if (array_key_exists(Constants::confirmRegistration, $_GET))
    {
        require_once 'protected/registrationConfirmer.php';
    }
    elseif (array_key_exists(Constants::passwordRestore, $_GET))
    {
        require_once 'protected/passwordGetRestorer.php';
    }
    elseif (getFirstWordBeforeQuestionMark() === 'oauthyandex')
    {
        require_once 'protected/oauthYandex.php';
    }
    else
    {
        require_once 'protected/pagePrinter.php';
    }
}


function getFirstWordBeforeQuestionMark(): string
{
    //returns first word from URI before question mark
    //letme.ijn.su/authyandex

    return preg_replace('/\/(\w*)?.*/', '$1', $_SERVER['REQUEST_URI']);
}


?>