<?php

namespace Helpers\Organizers;


require_once 'languages.php';

class UserErrors {

    const emailNotValid = 'errorEmailNotValid';

    const emailExist = 'errorEmailExist';

    const passwordUnsafe = 'errorPasswordUnsafe';


    const emptyNote = [
        Languages::russian => 'Заметка не должна быть пустой. Кстати, запятые и пробелы не считаются :-)',
        Languages::english => 'Note should not be empty. By the way we do not count spaces, commas etc :-)',
        Languages::spanish => 'La nota no debe estar vacía. Por cierto, no contamos espacios, comas, etc :-)'
    ];


    const emailPasswordWrong = [
        Languages::russian => 'Email или пароль неверны',
        Languages::english => 'Email or password is wrong',
        Languages::spanish => 'El email o la contraseña son incorrectos'
    ];

    const passwordIsTooShort = [
        Languages::russian => 'Пароль короткий, 4 символа минимум',
        Languages::english => 'Password is too short, 4 symbols minimum',
        Languages::spanish => 'La contraseña es demasiado corta, mínimo 4 símbolos'
    ];

}

?>