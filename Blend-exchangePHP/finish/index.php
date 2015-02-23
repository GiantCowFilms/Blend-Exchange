<html>
    <?php
    //Get information from form
    $questionUrl = $_GET["url"];
    if(!preg_match('/http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z-#0-9]+$/',$questionUrl)){
        echo "Invalid url";
        exit;
    };
    $password =$_GET["password"];
    
    //Get file 
    //$blob = file_get_contents("../document.txt");
    //$blob = fopen($_FILES['file']['tmp_name'], "rb");
    //Get Oauth keys
    $secretKeys = json_decode(file_get_contents("../secret.json"));
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
    
    //Insert a file
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($_FILES['file']["name"]);
    $file->setDescription('A test document');
    $file->setMimeType('text/plain');

    $data = file_get_contents($_FILES['file']['tmp_name']);
    $dataSize = filesize($_FILES['file']['tmp_name']);
    
    $createdFile = $service->files->insert($file, array(
          'data' => $data,
          'mimeType' => 'application/octet-stream',
          'uploadType' => 'media'
        ));
    //echo'<pre>';
    //echo $createdFile["downloadUrl"];
    //echo '</pre>';
    
    ?>
    <?php
    
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    $db->prepare("INSERT INTO `blends` SET `id`=NULL, `fileName`='".$_FILES['file']["name"]."', `fileUrl`='".$createdFile["downloadUrl"]."', `flags`='', `views`=0, `downloads`=0, `password`='".$password."', `uploaderIp`='NOTSUPPORTED', `questionLink`='".$questionUrl."', `fileSize`='".$dataSize."'")->execute();
    $blendId = $db->lastInsertId("Id");
    $blendData["id"] = $blendId;
    $blendData["fileName"] = $_FILES['file']["name"];
    $blendData["questionLink"] = $questionUrl;
    $blendData["fileSize"] = $dataSize;
    ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/downloadPage.php"); ?>
    <?php include("../parts/footer.php"); ?>
