<?php declare(strict_types=1);

namespace BlendExchange\User\Data;

use BlendExchange\User\Model\User;
use BlendExchange\Client\StackExchangeClient;
use AlexMasterov\OAuth2\Client\Provider;

final class StackExchangeRepository 
{
    private $oauthClient;
    private $stackExchangeClient;

    public function __construct(Provider\StackExchange $oauthClient,StackExchangeClient $stackExchangeClient)
    {
        $this->oauthClient = $oauthClient;
        $this->stackExchangeClient = $stackExchangeClient;
    }

    public function getAccessTokenByCode (string $code) : string
    {
        $accessToken = $this->oauthClient->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        return $accessToken->getToken();
    }

    public function getUserByAccessToken (string $accessToken) : object
     {
        $stackUser = $this->stackExchangeClient->getUserFromAccessToken($accessToken);

        return $stackUser;
    }
}