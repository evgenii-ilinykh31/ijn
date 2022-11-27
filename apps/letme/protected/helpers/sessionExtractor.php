<?php

namespace Helpers;


require_once 'models/sessions.php';
require_once 'organizers/constants.php';

use Helpers\Models\Sessions;
use Helpers\Organizers\Constants;

class SessionExtractor {


    private function __construct()
    {
    }

    public static function getId(): int
    {

        if (
            array_key_exists(Constants::sessionName, $_COOKIE)
            && array_key_exists(Constants::sessionPassword, $_COOKIE)
            && Sessions::getPassword($_COOKIE[Constants::sessionName]) === $_COOKIE[Constants::sessionPassword]
        )
        {
            $id = Sessions::getId($_COOKIE[Constants::sessionName]);

            Sessions::setLastChange($id);

            return $id;
        }

        return 0;
    }

}

?>