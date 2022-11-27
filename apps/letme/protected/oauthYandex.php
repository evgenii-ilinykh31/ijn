<?php

require_once 'protected/helpers/organizers/oauthYandexData.php';
require_once 'protected/helpers/sessionCreator.php';
require_once 'protected/helpers/workers/utf8RandomString.php';


//заплатки:
require_once 'protected/helpers/models/users.php';
require_once 'protected/helpers/workers/languagesCode.php';
require_once 'protected/helpers/userCreator.php';
require_once 'protected/helpers/confirmNote.php';
require_once 'protected/helpers/appPage.php';
require_once 'protected/helpers/sessionExtractor.php';
require_once 'protected/helpers/organizers/constants.php';



use Helpers\AppPage;
use Helpers\ConfirmNote;
use Helpers\Models\Users;
use Helpers\Organizers\OauthYandexData;
use Helpers\SessionCreator;
use Helpers\UserCreator;
use Helpers\Workers\LanguagesCode;
use Helpers\Workers\Utf8RandomString;
use Helpers\Organizers\Constants;


class OauthYandex {

    protected $languageCode, $userId, $csrfToken;

    public function main()
    {
        //get user email
        $info = $this->getUserOAuthInfo();

        if ( ! array_key_exists('default_email', $info))
        {
            exit('Error 404');
        }

        $userEmail = $info['default_email'];

        //check ifUserExists
        $this->userId = intval(Users::getId($userEmail));

        if ( ! $this->userId)
        {
            $userPassword = Utf8RandomString::get(4);
            $this->userId = $this->registerNewUserAndGetId($userEmail, $userPassword);
        }

        $session = new SessionCreator($this->userId);

        header('Location: ' . Constants::appLink);

        print AppPage::getJobMode($session->getId());

    }

    protected function getUserOAuthInfo(): array
    {
        $state = $_GET['state']; // у нас state пустой

        $info = [];

        $oauthYandex = new OauthYandexData();

        if ( ! empty($_GET['code']))
        {
            // Отправляем код для получения токена (POST-запрос).
            $params = array(
                'grant_type'    => 'authorization_code',
                'code'          => $_GET['code'],
                'client_id'     => $oauthYandex->getClientId(),
                'client_secret' => $oauthYandex->getClientSecret(),
            );

            $ch = curl_init('https://oauth.yandex.ru/token');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $data = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($data, true);
            if ( ! empty($data['access_token']))
            {
                // Токен получили, получаем данные пользователя.
                $ch = curl_init('https://login.yandex.ru/info');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array('format' => 'json'));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $data['access_token']));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $info = curl_exec($ch);
                curl_close($ch);

                $info = json_decode($info, true);

            }

            return $info;
        }
    }

    protected function registerNewUserAndGetId($userEmail, $userPassword): int
    {
        $languageCode = LanguagesCode::getLanguageCodeGeneralWayForSession();
        $user = new UserCreator($userEmail, $userPassword, $languageCode);
        $confirmNoteId = ConfirmNote::createOauthGetId(intval($user->getId()), $languageCode, $userEmail, $userPassword);

        return intval($user->getId());
    }
}

$oauthYandex = new OauthYandex();

$oauthYandex->main();