<html>
    <?php
    $blendId = $_GET["blendId"];
    $secretKeys = json_decode(file_get_contents("../secret.json"));
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    $blendData = $db->query("SELECT `id`, `fileName`, `fileUrl`, `flags`, `views`, `downloads`, `password`, `uploaderIp`, `questionLink`, `fileSize` FROM `blends` WHERE `id`=" . $blendId);
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    ?>
    <?php include("../parts/header.php"); ?>

    <?php include("../parts/downloadPage.php"); ?>