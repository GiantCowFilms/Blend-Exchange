<html>
    <?php
    $blendId = $_GET["blendId"];
    $blendId = intval($blendId);
    $secretKeys = json_decode(file_get_contents("../secret/secret.json"));
    
    include("../parts/database.php");
    
    $blendData = $db->prepare("SELECT `id`, `fileName`, `fileGoogleId`, `flags`, `password`, `uploaderIp`, `questionLink`, `fileSize`,`adminComment`,`deleted` FROM `blends` WHERE `id`= :id");
    $blendData->execute(array('id' => $blendId));
    //If there are no rows, no file
    $fileExists = ($blendData->rowCount() != 0);
    if($fileExists){
        $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    } else {
        $blendData = [];
    }
    
    $blendData["fileExists"] = $fileExists;
    
    //New better view counter
    //Get IP adress
    $ipAdress = $_SERVER['REMOTE_ADDR'];
    $ipAdress = hash("sha256", $ipAdress, false); 
    
    $referingAdress = '';
    if(isset($_SERVER['HTTP_REFERER'])) {
        $referingAdress = $_SERVER['HTTP_REFERER'];
        //Process URL to get rid of stuff after the last slash
        $notBlank = strlen($referingAdress) > 0;
        $matches = [];
        if (preg_match('/^http:\/\/blender.stackexchange.com\/questions\/[0-9]+\/[a-z-]+/', $referingAdress, $matches)){
                $referingAdress = $matches["0"];
        }
    }
    
    $db->prepare("INSERT INTO `accesses` SET `ref`=:ref, `type`='view', `ip`='".$ipAdress."', `fileId`=:fileId, `date`=NOW()")->execute(array('fileId' => $blendId,'ref' => $referingAdress));
    
    //Read download count
    $rows = $db->prepare("SELECT `ip` FROM `accesses` WHERE `type`='view' AND `fileId`=:fileId");
    $rows->execute(array('fileId' => $blendId));
    $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
    $ips = [];
    foreach ($rows as $key => $row)
    {
        $remove = false;
        foreach ($ips as $ip)
        {
            if($ip == $row["ip"]){
                unset($rows[$key]);
                $remove = true;
                break;
            }
        }
        if($remove == false){
            $ips[] = $row["ip"];
        }
    }
    $blendData["views"] = count($rows);
    
    
    
    $rows = $db->prepare("SELECT `ip` FROM `accesses` WHERE `type`='download' AND `fileId`=:fileId");
    $rows->execute(array('fileId' => $blendId));
    $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
    $ips = [];
    foreach ($rows as $key => $row)
    {
      $remove = false;
      foreach ($ips as $ip)
      {
          if($ip == $row["ip"]){
              unset($rows[$key]);
              $remove = true;
              break;
          }
      }
      if($remove == false){
          $ips[] = $row["ip"];
      }
    }

    $blendData["downloads"] = count($rows);
    
    $rows = $db->prepare("SELECT `ip` FROM `accesses` WHERE `type`='favorite' AND `fileId`=:fileId");
    $rows->execute(array('fileId' => $blendId));
    $rows = $rows->rowCount();
    $blendData["favorites"] = $rows;
    
    $rows = $db->prepare("SELECT `val`,`id`,`accept` FROM `accesses` WHERE `type`='flag' AND `fileId`=:fileId");
    $rows->execute(array('fileId' => $blendId));
    $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
    $blendData["flags"] = $rows;
    
    if(!$blendData["fileExists"]){
        header("HTTP/1.0 404 Not Found");
    }
    ?>
    <?php include("../parts/header.php"); ?>
    <?php 
    if(!$blendData["fileExists"]){
        echo "            <div class=\"noticeWarning nwDanger\">
                    Blend file not found
                </div>";
        exit();
    }
    ?>
    <?php include("../parts/downloadPage.php"); ?>
    <?php include("../parts/footer.php"); ?>