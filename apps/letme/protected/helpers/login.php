<?php

namespace Helpers;


require_once 'models/users.php';
require_once 'organizers/languages.php';
require_once 'organizers/userErrors.php';


use Helpers\Models\Users;
use Helpers\Organizers\Languages;
use Helpers\Organizers\UserErrors;


class Login {

    protected $loginError = [];

    protected $userId;

    protected $isLoginSuccessful;


    public function __construct(string $email, string $password)
    {
        $this->login($email, $password);
    }


    public function getUserId(): int
    {
        return $this->userId;
    }


    public function isLoginSuccessful(): bool
    {
        return $this->isLoginSuccessful;
    }


    public function getLoginError(string $languageCode = Languages::english): string
    {
        return $this->loginError[$languageCode];
    }


    protected function login($email, $password)
    {
        $this->userId = Users::getId($email);

        if ( ! $this->userId)
        {
            $this->isLoginSuccessful = false;
            $this->loginError = UserErrors::emailPasswordWrong;

            return;
        }

        if ( ! password_verify($password, Users::getPassword($this->userId)))
        {
            $this->isLoginSuccessful = false;
            $this->loginError = UserErrors::emailPasswordWrong;

            return;
        }

        $this->isLoginSuccessful = true;

    }

}

?>