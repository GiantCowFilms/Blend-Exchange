 <?php
    $id = $_GET["id"];
    
    $secretKeys = json_decode(file_get_contents("../secret/secret.json"));
    
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    
    $blendData = $db->query("SELECT `flags` FROM `blends` WHERE `id`=" . $id);
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    
    $flags = $blendData["flags"];
    
    $flags = $flags.$offsense.";";
    
    //Get IP adress
    $ipAdress = $_SERVER['REMOTE_ADDR'];
    $ipAdress = hash("sha256", $ipAdress, false); //Use this to catch duplicates
    
    $db->prepare("UPDATE `blends` SET `flags`='".$flags."' WHERE `id`='".$id."'")->execute();
    $blendId = $db->lastInsertId("Id");
    
    $db->prepare("INSERT INTO `accesses` SET `type`='flag', `ip`='".$ipAdress."', `val`='".$offsense."', `fileId`='".$id."', `date`=NOW()")->execute();
    $blendId = $db->lastInsertId("Id");
?>
