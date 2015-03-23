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
    preg_match('/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z0-9-]+/', $questionUrl, $matches);
    $questionUrl = $matches["0"];
    
    $password = "";
    
    if(isset($_GET["password"])){
        $password = $_GET["password"];
    }
    
    include("../parts/googleDriveAuth.php");
    
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
    $ipAdress = hash("sha256", $ipAdress, false);
    
    include("../parts/checkLogin.php");
    
    if($loggedIn == false){
        $userId = 0;
    }
    
    include("../parts/database.php"); 
    $db->prepare("INSERT INTO `blends` SET `id`=NULL, `fileName`=:fileName, `fileGoogleId`='".$createdFile->id."', `flags`='', `views`=0, `downloads`=0, `password`=:password, `uploaderIp`='".$ipAdress."', `questionLink`='".$questionUrl."', `fileSize`='".$dataSize."', `date`=NOW(), `owner`=:uid")->execute(
        array(
        'fileName' => $_FILES['file']["name"],
        'password' => hash("sha256", $password, false),
        'uid' => $userId
        )
    );
   
    
    $blendId = $db->lastInsertId("Id");
    
    //Remove just cause!
    if($loggedIn == false){
        unset($userId);
    }
    
    $blendData["id"] = $blendId;
    $blendData["fileName"] = $_FILES['file']["name"];
    $blendData["questionLink"] = $questionUrl;
    $blendData["fileSize"] = $dataSize;
    $blendData["views"] = 0;
    $blendData["downloads"] = 0;
    $blendData["flags"] = [];
    $blendData["favorites"] = 0;
    $blendData["adminComment"] = "";
    $blendData["deleted"] = 0;
    ?>
    <?php include("../parts/header.php"); ?>
    <?php include("../parts/downloadPage.php"); ?>
    <?php include("../parts/footer.php"); ?>
