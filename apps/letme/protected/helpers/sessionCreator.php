<?php

namespace Helpers;


require_once 'models/sessions.php';
require_once 'workers/utf8RandomString.php';
require_once 'organizers/constants.php';


use Exception;
use Helpers\Models\Sessions;
use Helpers\Workers\Utf8RandomString;
use Helpers\Organizers\Constants;

class SessionCreator {

    protected $id;
    protected $name;
    protected $password;
    protected $tokenCsrf;

    public function __construct($userId)
    {
        $this->create($userId);
    }

    public function getTokenCsrf(): string
    {
        if ($this->tokenCsrf)
        {
            return $this->tokenCsrf;
        }
        else
        {
            throw new Exception('Попытка выдать нулевой csrf-токен сессии');
        }
    }


    public function getId(): int
    {
        return intval(Sessions::getId($this->name));
    }


    protected function create(int $userId): void
    {
        $this->name = $this->getUniqueName();

        $this->password = Utf8RandomString::get(Constants::sessionPasswordLength);

        $this->tokenCsrf = Utf8RandomString::get(Constants::tokenCsrfLength);

        Sessions::insertNew($userId, $this->name, $this->password, $this->tokenCsrf);

        $this->set();
    }


    protected function set()
    {

        if ($this->name && $this->password)
        {
            setcookie(Constants::sessionName, $this->name, time() + 3600 * 24 * 365);
            setcookie(Constants::sessionPassword, $this->password, time() + 3600 * 24 * 365);
        }
        else
        {
            throw new Exception('Попытка установки нулевых имени и пароля сессии');
        }
    }

    protected function getUniqueName(): string
    {
        $temporaryName = Utf8RandomString::get(Constants::sessionNameLength);

        while (Sessions::getId($temporaryName))
        {
            $temporaryName = Utf8RandomString::get(Constants::sessionNameLength);
        }

        return $temporaryName;
    }

}

?>