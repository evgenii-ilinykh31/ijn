<?php

namespace Helpers\Organizers;


require_once 'languages.php';


class Errors {

    const commonError = [
        Languages::russian => 'Ошибка. Перезагрузите, пожалуйста, страницу',
        Languages::english => 'Error. Reload page, please',
        Languages::spanish => 'Error. Vuelva a cargar la página, por favor'
    ];


    const noPage = [
        Languages::russian => 'Ошибка. Такой страницы у нас нет. Но вы можете перейти на нашу главную страницу:',
        Languages::english => 'Error. There is no page. But you can go to our main page:',
        Languages::spanish => 'Error. No hay página. Pero puedes ir a nuestra página principal:'
    ];


    const noPermission = [
        Languages::russian => 'Ошибка. У вас нет прав на выполнение данного действия. Перезагрузите страницу.',
        Languages::english => 'Error. You do not have permission to do this. Reload page, please.',
        Languages::spanish => 'Error. Usted no tiene permiso para hacer esto Vuelva a cargar la página, por favor.'
    ];

    private function __construct()
    {
    }

}

?>