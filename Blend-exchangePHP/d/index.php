<?php
    //Get file 
    //$blob = file_get_contents("../document.txt");
    //$blob = fopen($_FILES['file']['tmp_name'], "rb");
    //Get Oauth keys
    $secretKeys = json_decode(file_get_contents("../secret/secret.json"));
    $Key = $secretKeys->key;
    $Cid = $secretKeys->cid;
    $Secret = $secretKeys->secret;
    $AccessToken = $secretKeys->accessToken;
    $RefreshToken = $secretKeys->refreshToken;
    
    //Load dropbox library
    require_once '../Google_Drive_Api/autoload.php';

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
    
    $blendId = $_GET["blendId"];
    
    include("../parts/database.php");
    
    $blendData = $db->prepare("SELECT `id`, `fileName`, `fileGoogleId`, `flags`, `views`, `downloads`, `password`, `uploaderIp`, `questionLink`, `fileSize` FROM `blends` WHERE `id`= :id");
    $blendData->execute(array('id' => $blendId));
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    
    $blendData["downloads"] = intval($blendData["downloads"]);
    $blendData["downloads"]++;
    $db->prepare("UPDATE `blends` SET `downloads`='".$blendData["downloads"]."' WHERE `id`='".$blendId."'")->execute();
    
    $file = $service->files->get($blendData["fileGoogleId"]);
    $request = new Google_Http_Request($file->getDownloadUrl(), 'GET', null, null);
    $SignhttpRequest = $client->getAuth()->sign($request);
    $httpRequest = $client->getIo()->makeRequest($SignhttpRequest);
    
    //Set header
    
    header('Content-type:  application/x-blender');
   
    echo $httpRequest->getResponseBody();
    
    ?>