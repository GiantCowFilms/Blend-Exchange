<html>
    <?php
    $blendId = $_GET["blendId"];
    $secretKeys = json_decode(file_get_contents("../secret.json"));
    $db = new PDO("mysql:host=".$secretKeys->mysql->host.";dbname=".$secretKeys->mysql->database,$secretKeys->mysql->user,$secretKeys->mysql->password);
    $blendData = $db->query("SELECT `id`, `fileName`, `fileUrl`, `flags`, `views`, `downloads`, `password`, `uploaderIp`, `questionLink`, `fileSize` FROM `blends` WHERE `id`=" . $blendId);
    $blendData = $blendData->fetchAll(PDO::FETCH_ASSOC)["0"];
    ?>
    <?php include("../parts/header.php"); ?>
        <div id="uploadContainer">
            <div id="uploadTarget">
                        <h2>
                            <?php echo $blendData["fileName"] ?>
                        </h2>
                        <span>
                             - <?php echo intval($blendData["fileName"]/1000000); ?> MB | <?php echo $blendData["questionLink"] ?>
                        </span>
            </div>
            <div id="uploadOptions">
                <div id="cancel" class="btnBlue" style="width: 289px; display: inline-block;">
                    Flag
                </div><div id="upload" class="btnBlue" style="width: 289px; margin-left: 10px; display: inline-block;">
                    <a href="/d/<?php echo $blendData["id"] ?>/<?php echo $blendData["fileName"] ?>">Download</a>
                </div>
            </div>
            <div>Embed:</div>
            <div id="embedCode">
                [Download the .blend file hosted by blend-exchange](http://blend-exchange.giantcowfilms.com/b/<?php echo $blendId; ?>);
            </div>
            <div id="usageNotice">
                <h2>
                    Disclaimer:
                </h2>
                Download this file at your own risk. It could contain viruses or other harmful material.
            </div>
        </div>
        <script src="jquery.js"></script>
        <script src="dropzone.js"></script>
        </script>
    </body>
</html>
