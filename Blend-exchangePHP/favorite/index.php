    <?php
    $blendId = $_GET["id"];
    $blendId = intval($blendId);
    
    include("../parts/database.php");
    
    //Get IP adress
    $ipAdress = $_SERVER['REMOTE_ADDR'];
    $ipAdress = hash("sha256", $ipAdress, false); 
    
    $blendData = $db->prepare("SELECT `ip` FROM `accesses` WHERE `type`='favorite' AND `fileId`=:fileId AND `ip`='".$ipAdress."'");
    $blendData->execute(array('fileId' => $blendId));
    $rows = $blendData->rowCount();
    if ( $rows == 0 ){
        $db->prepare("INSERT INTO `accesses` SET `type`='favorite', `ip`='".$ipAdress."', `fileId`=:fileId, `date`=NOW()")->execute(array('fileId' => $blendId));
        echo 'Favorited!';
    }  else {
        echo 'Someone form this IP has already favorited this .blend file';
    }
    ?>
