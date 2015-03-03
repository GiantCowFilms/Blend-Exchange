<html>
    <?php
    //Get information from form
    $questionUrl = $_GET["url"];
    if(!preg_match('/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z-#0-9\/_?=]+$/',$questionUrl)){
        echo "Invalid url";
        exit;
    };
    //Process URL to get rid of stuff after the last slash
    
    $matches = [];
    preg_match('/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\//', $questionUrl, $matches);
    $questionUrl = $matches["0"];
    
    $password = $_GET["password"];
    
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
    
    //Insert a file
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($_FILES['file']["name"]);
    $file->setDescription('A test document');
    $file->setMimeType('text/plain');

    $data = fopen($_FILES['file']['tmp_name'],"rb");
    $dataSize = filesize($_FILES['file']['tmp_name']);
    
    //$createdFile = $service->files->insert($file, array(
    //      'data' => $data,
    //      'mimeType' => 'application/octet-stream',
    //      'uploadType' => 'media'
    //    ));
    ////echo'<pre>';
    ////var_dump($createdFile["id"]);
    ////echo '</pre>';
    
    //New big file upload
    
    $client->setDefer(true);
    $request = $service->files->insert($file);
    //Set size of chuncks for upload
    $chunkSizeBytes = 1 * 1024 * 1024;
    
    // Create a media file upload to represent our upload process.
    $media = new Google_Http_MediaFileUpload(
      $client,
      $request,
      'application/octet-stream',
      null,
      true,
      $chunkSizeBytes
    );
    $media->setFileSize($dataSize);

    // Upload the various chunks. $status will be false until the process is
    // complete.
    $status = false;
    $handle = $data;
    while (!$status && !feof($handle)) {
        $chunk = fread($handle, $chunkSizeBytes);
        $status = $media->nextChunk($chunk);
    }

    // The final value of $status will be the data from the API for the object
    // that has been uploaded.
    $result = false;
    if($status != false) {
        $result = $status;
    }

    fclose($handle);
    // Reset to the client to execute requests immediately in the future.
    $client->setDefer(false);    
    
    $createdFile = $result;
    
    //Get IP adress
    $ipAdress = $_SERVER['REMOTE_ADDR'];
    $ipAdress = hash("sha256", $ipAdress, false);;

    include("../parts/database.php"); 
    $db->prepare("INSERT INTO `blends` SET `id`=NULL, `fileName`=:fileName, `fileGoogleId`='".$createdFile->id."', `flags`='', `views`=0, `downloads`=0, `password`=:password, `uploaderIp`='".$ipAdress."', `questionLink`='".$questionUrl."', `fileSize`='".$dataSize."'")
    ->execute(
        array(
        'fileName' => $_FILES['file']["name"],
        'password' => $ipAdress = hash("sha256", $password, false)
        )
    );
    $blendId = $db->lastInsertId("Id");
    $blendData["id"] = $blendId;
    $blendData["fileName"] = $_FILES['file']["name"];
    $blendData["questionLink"] = $questionUrl;
    $blendData["fileSize"] = $dataSize;
    $blendData["views"] = 0;
    $blendData["downloads"] = 0;
    $blendData["flags"] = [];
    $blendData["favorites"] = 0;
    $blendData["adminComment"] = "";
    ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/downloadPage.php"); ?>
    <?php include("../parts/footer.php"); ?>
