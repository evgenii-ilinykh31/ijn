<?php

namespace Helpers;


require_once 'models/users.php';

use Helpers\Models\Users;

class UserCreator {

    protected $id;

    public function __construct($email, $password, $language)
    {
        $this->id = Users::insertNewGetId($email, password_hash($password, PASSWORD_DEFAULT), $language);
    }

    public function getId()
    {
        return $this->id;
    }


}

?>