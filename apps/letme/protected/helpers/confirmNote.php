<?php

namespace Helpers;


require_once 'protected/helpers/organizers/languages.php';

use Helpers\Models\Notes;
use Helpers\Organizers\Languages;

class ConfirmNote {

    const text = [
        Languages::russian => [
            'О РЕГИСТРАЦИИ:<br />' .
            'На email, указанный при регистрации, отправлена ссылка. <br />' .
            'Перейдите по ней, чтобы поддтвердить аккаунт.  <br />' .
            'Не подтвержденные аккаунты удаляются в течении 3х дней.  <br /><br />' .
            'КАК ИСПОЛЬЗОВАТЬ:  <br />' .
            '• Два клика(тапа) - создать заметку<br />' .
            '• Три клика(тапа) - поиск по заметкам<br />' .
            '• Для мобильной версии - свайп влево - вызову меню "удалить/редактировать заметку"<br />' .
            '• Вся информация продублирована в разделе "Настройки -> Как пользоваться"'
        ],
        Languages::english => [
            'ABOUT REGISTRATION:<br />' .
            'A link has been sent on your email. <br />' .
            'Follow it to confirm your account. <br />' .
            'Account without confirmation would be deleted next three days.  <br /><br />' .
            'HOW TO USE:  <br />' .
            '• Double click/tap - create note<br />' .
            '• Triple click/tap - search in notes<br />' .
            '• For mobile version - swipe left - call "delete/edit note" menu<br />' .
            '• All information is duplicated in "Settings -> How to use" section'
        ],
        Languages::spanish => [
            'ACERCA DEL REGISTRO:<br />' .
            'Se ha enviado un enlace a su email. <br />' .
            'Síguelo para confirmar tu cuenta. <br />' .
            'La cuenta sin confirmación se eliminará los próximos tres días.  <br /><br />' .
            'CÓMO UTILIZAR:  <br />' .
            '• Doble clic / toque - crear nota<br />' .
            '• Triple clic / toque - buscar en notas<br />' .
            '• Para la versión móvil - deslice hacia la izquierda - llame al menú "eliminar / editar nota"<br />' .
            '• Toda la información está duplicada en la sección "Configuración -> Cómo usar"'
        ]
    ];

    const oAuthText = [
        Languages::russian => [
            '<br /><br />' .
            'Вы зарегистрировались с помощью технологии oAuth.<br />' .
            'Ваш email и пароль мы прислали ниже.<br />' .
            'В следующий раз Вы можете зайти на сайт не только с помощью oAuth, но и через пару email/пароль.' .
            'Если Вам не нравится наш пароль - Вы можете изменить его в любой момент в настройках.'
        ],
        Languages::english => [
            '<br /><br />' .
            'You have registered with oAuth technology.<br />' .
            'We have sent your email and password below.<br />' .
            'Next time you can enter the site not only using oAuth, but also through a pair of email / password.' .
            'If you do not like our password, you can change it at any time in the settings.'
        ],
        Languages::spanish => [
            '<br /><br />' .
            'Te has registrado con la tecnología oAuth.<br />' .
            'Hemos enviado su correo electrónico y contraseña a continuación.<br />' .
            'La próxima vez puede ingresar al sitio no solo usando oAuth, sino también a través de un par de correo electrónico / contraseña.' .
            'Si no le gusta nuestra contraseña, puede cambiarla en cualquier momento en la configuración.'
        ]
    ];

    public static function createGetId($userId, $languageCode): string
    {
        require_once 'models/notes.php';

        return Notes::insertNewGetId($userId, self::getTranslation($languageCode));

    }

    public static function createOauthGetId($userId, $languageCode, $email, $password): string
    {
        require_once 'models/notes.php';

        return Notes::insertNewGetId($userId, self::getTranslation($languageCode) . self::oAuthText[$languageCode][0] . '.<br />' . $email . '.<br />' . $password);
    }

    public static function getTranslation(string $languageCode): string
    {
        return self::text[$languageCode][0];
    }

}

?>