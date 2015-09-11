<?php
$secretKeys = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/secret/secret.json"));
$Key = $secretKeys->key;
$Cid = $secretKeys->cid;
$Secret = $secretKeys->secret;
$AccessToken = $secretKeys->accessToken;
$RefreshToken = $secretKeys->refreshToken;

//Load dropbox library
require_once($_SERVER["DOCUMENT_ROOT"].'/Google_Drive_Api/autoload.php');

$client = new Google_Client();
// Get your credentials from the console
$client->setClientId($Cid);
$client->setClientSecret($Secret);
$AccessTokenJson = '{
        "access_token": "' . $AccessToken . '",
        "token_type": "Bearer",
        "expires_in": 3600,
        "refresh_token": "' . $RefreshToken . '",
        "created": 1424627698
    }';
$client->setAccessToken($AccessTokenJson);

$service = new Google_Service_Drive($client);
?>