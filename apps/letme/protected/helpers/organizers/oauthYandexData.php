<?php


namespace Helpers\Organizers;


class OauthYandexData {

    protected $client_id = 'd2c2d5f4b2ea489c9ae2e4b0b893157e';

    protected $redirect_uri = 'http://letme.ijn.su/oauthyandex';

    protected $responseType = 'code';

    protected $state = '';


    protected $password = 'd553917baa2b4d7984f85836e8154373';


    public function getUrl(): string
    {
        return 'https://oauth.yandex.ru/authorize?' . urldecode(http_build_query($this->getParams()));
    }

    protected function getParams(): array
    {
        return array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => $this->responseType,
            'state'         => $this->state
        );
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getClientSecret(): string
    {
        return $this->password;
    }

}