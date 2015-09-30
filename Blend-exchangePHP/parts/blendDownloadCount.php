<?php

include($_SERVER["DOCUMENT_ROOT"]."/parts/database.php");

function getDownloadCount ($blendId) 
{
    global $db;
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

    return count($rows);
}
?>