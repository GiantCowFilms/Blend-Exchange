<html>
    <?php
    $blendId = $_GET["blendId"];
    $blendId = intval($blendId);
    $secretKeys = json_decode(file_get_contents("../secret/secret.json"));
    
    include("../parts/database.php");
    
    $blendData = $db->prepare("SELECT `id`, `fileName`, `fileGoogleId`, `flags`, `views`, `downloads`, `password`, `uploaderIp`, `questionLink`, `fileSize` FROM `blends` WHERE `id`= :id");
    $blendData->execute(array('id' => $blendId));
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    $blendData["views"] = intval($blendData["views"]);
    $blendData["views"]++;
    $db->prepare("UPDATE `blends` SET `views`='".$blendData["views"]."' WHERE `id`='".$blendId."'")->execute();
    
    ?>
    <?php include("../parts/header.php"); ?>

    <?php include("../parts/downloadPage.php"); ?>
    <?php include("../parts/footer.php"); ?>