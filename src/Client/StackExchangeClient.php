<?php  declare(strict_types = 1);
namespace BlendExchange\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Due to the lack of complete PHP StackExchange API clients, I have created this class which is a sloppy wrapper for the functionality that is needed. 
 */
class StackExchangeClient {
    private $client;
    private $authenticated_client;
    private $clientId;
    private $clientSecret;
    private $key;
    private $token;
    
    function __construct ($clientId,$clientSecret,$key) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->key = $key;
        //Client is not injected since it is immutable and there is no easy way to define parameters
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/'
        ]);
    }

    private function getAuthParams() {
        return [
            'key' => $this->key
        ];
    }

    function getOauthRedirectUri() {
        $data = [
            'client_id' => $this->clientId,
            'scope' => 'no_expiry',
            'redirect_uri' => $this->getSelfRedirectUri(),
        ];
        return 'https://stackoverflow.com/oauth?' . http_build_query($data);
    }
    function getAccessTokenFromOAuthResponse ($code) {
        $oauthClient = new \GuzzleHttp\Client();
    $response = $oauthClient->request('POST','https://stackoverflow.com/oauth/access_token',[
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->getSelfRedirectUri(),
            ]
        ]);
        $this->token = $response->getBody()->getContents();
        $this->token = substr($this->token,13);
        $this->upgradeToUserClient();
        return  $this->token;
    }

    public function upgradeToUserClient () {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/',
            'headers' => ['X-API-Access-Token' => $this->token,
            'X-API-Key' => $this->key]
        ]);
    }

    /**
     * 
     */
    function getUserFromAccessToken ($access_token) {
        //IMPORTANT!!!! MUST GET NETWORK ID NOT SITE ID.
        $response = $this->client->request('GET',sprintf('/me'),[
            'headers' => [
                'X-API-Access-Token' => $access_token,
                'X-API-Key' => $this->key
            ],
            'query' => [
                'site' => 'blender'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $data->items[0];
    }

    function getQuestion ($id) {
        $response = $this->client->request('GET',sprintf('/questions/%s',$id),[
            'query' => array_merge($this->getAuthParams(),[
                'site' => 'blender'
            ])
        ]);
        $data = json_decode($response->getBody()->getContents());
        if (count($data->items) > 0) {
            return $data->items[0];
        } else {
            return null;
        }
    }
}