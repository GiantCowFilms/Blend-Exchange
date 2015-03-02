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
            //TODO
            $db->prepare("UPDATE `accesses` SET `accepet`=:accept WHERE `id`=:flagId")
            ->execute(
                array(
                'flagId' => $flagId,
                'accept' => $type
                )
            );
            break;
    }
    
?>