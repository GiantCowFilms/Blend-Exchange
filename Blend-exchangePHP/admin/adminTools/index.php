<?php include("../../parts/requireLogin.php"); ?>
<?php include("../../parts/database.php"); ?>
<?php 
    $act = $_POST["act"];
    $blendId = $_POST["fileId"];
    switch ($act)
    {
    	case "Comment":
            //Admin comment
            $comment = $_POST["text"];
            
            $db->prepare("UPDATE `blends` SET `adminComment`=:comment WHERE `id`=:blendId")
            ->execute(
                array(
                'blendId' => $blendId,
                'comment' => $comment
                )
            );
            break;
        case "actOnFlag":
            $flagId = $_POST["flagId"];
            $type = $_POST["type"];
            $type = ($type == "accept")? 1 : 2;
            $test = $db->prepare("UPDATE `accesses` SET `accept`=:accept WHERE `id`=:flagId");
            var_dump($test);
            $test->execute(
                array(
                'flagId' => $flagId,
                'accept' => $type
                )
            );
            break;
        case "setValid":
            $type = $_POST["type"];
            $db->prepare("UPDATE `blends` SET `valid`=:valid WHERE `id`=:blendId")
            ->execute(
                array(
                'blendId' => $blendId,
                'valid' => $type
                )
            );
            break;
        case "delete":
            include("../../parts/googleDriveAuth.php");
            
            //Get .blend file google ID
            $gdId = $db->prepare("SELECT `fileGoogleId` FROM `blends` WHERE `id`=:blendId");
            $gdId->execute(
                    array(
                    'blendId' => $blendId,
                    )
                );
            $gdId = $gdId->fetchAll(PDO::FETCH_ASSOC)["0"]["fileGoogleId"];
            $service->files->delete($gdId);
            $db->prepare("UPDATE `blends` SET `deleted`=1 WHERE `id`=:blendId")
            ->execute(
                array(
                'blendId' => $blendId,
                )
            );
            echo "Deleted file id: ".PHP_EOL;
            echo $gdId;
            break;
            
    }
    
?>