 <?php
    $offsense = $_GET["flag"];
    $id = $_GET["id"];
    
    $secretKeys = json_decode(file_get_contents("../secret.json"));
    
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    
    $blendData = $db->query("SELECT `flags` FROM `blends` WHERE `id`=" . $id);
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    
    $flags = $blendData["flags"];
    
    $flags = $flags.$offsense.";";
    
    $db->prepare("UPDATE `blends` SET `flags`='".$flags."' WHERE `id`='".$id."'")->execute();
    $blendId = $db->lastInsertId("Id");
?>
