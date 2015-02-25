 <?php
    $id = $_GET["id"];
    $offsense = $_GET["flag"];
    
    $secretKeys = json_decode(file_get_contents("../secret/secret.json"));
    
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    
    $blendData = $db->prepare("SELECT `flags` FROM `blends` WHERE `id`=:id");
    $blendData->execute(array("id" => $id));
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    
    $flags = $blendData["flags"];
    
    $flags = $flags.$offsense.";";
    
    //Get IP adress
    $ipAdress = $_SERVER['REMOTE_ADDR'];
    $ipAdress = hash("sha256", $ipAdress, false); //Use this to catch duplicates
    
    $db->prepare("UPDATE `blends` SET `flags`='".$flags."' WHERE `id`=:id")->execute(array("id" => $id));
    $blendId = $db->lastInsertId("Id");
    
    $db->prepare("INSERT INTO `accesses` SET `type`='flag', `ip`='".$ipAdress."', `val`='".$offsense."', `fileId`=:fileId, `date`=NOW()")->execute(array("fileId" => $id));
    $blendId = $db->lastInsertId("Id");
    
    echo "File flagged, thank you for helping this community project";
?>
