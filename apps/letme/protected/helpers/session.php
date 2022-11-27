<?php

namespace Helpers;


require_once 'models/sessions.php';
require_once 'organizers/constants.php';


use Helpers\Models\Sessions;
use Helpers\Organizers\Constants;


class Session {

    protected $id;
    protected $tokenCsrf;
    protected $userId;

    public function __construct($id)
    {

        $this->id = $id;

        $this->userId = Sessions::getUserId($this->id);

        $this->tokenCsrf = Sessions::getTokenCsrf($this->id);

    }


    public function unset()
    {
        Sessions::delete($this->id);

        setcookie(Constants::sessionName, '', time() - 3600);
        setcookie(Constants::sessionPassword, '', time() - 3600);
    }


    public function getTokenCsrf()
    {
        return $this->tokenCsrf;
    }


    public function getUserId()
    {
        return $this->userId;
    }

}

?>