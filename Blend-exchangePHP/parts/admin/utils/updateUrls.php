<?php
include($_SERVER["DOCUMENT_ROOT"]."/parts/verifyUrl.php");
include($_SERVER["DOCUMENT_ROOT"]."/parts/database.php");

$r = $db->query("SELECT `id`,`questionLink` FROM `blends`");
$r = $r->fetchAll(PDO::FETCH_ASSOC);

foreach ($r as $blend)
{
    $blend["questionLink"] =  removeInvalid($blend["questionLink"]);
    //Process URL to get rid of stuff after the last slash

    $blend["questionLink"] = cleanUrl($blend["questionLink"]); 
    $r = $db->prepare("UPDATE `blends` SET `questionLink`=:q WHERE `id`=:id")->execute(["q"=>$blend["questionLink"],"id"=>$blend["id"]]);
}
$r = $db->query("SELECT `id`,`ref` FROM `accesses`");
$r = $r->fetchAll(PDO::FETCH_ASSOC);

foreach ($r as $blend)
{
    $blend["ref"] =  removeInvalid($blend["ref"]);
    //Process URL to get rid of stuff after the last slash

    $blend["ref"] = cleanUrl($blend["ref"]); 
    $r = $db->prepare("UPDATE `accesses` SET `ref`=:r WHERE `id`=:id")->execute(["r"=>$blend["questionLink"],"id"=>$blend["id"]]);
}
?>