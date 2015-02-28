<?php
if (!isset($flagType)){
    $flagType = "all";
}

include("../parts/database.php");

//Autoflag:
//Looks for blends that have less then half of there refs from thier question link
//Query created with the help of TehShrike http://stackoverflow.com/users/201789/tehshrike
$autoFlag = $db->prepare("SELECT `blends`.`id`,`blends`.`fileName`,`blends`.`questionLink`,`accesses`.`val`, SUM(`accesses`.`ref` != `blends`.`questionLink`) AS firstPart, SUM(`accesses`.`ref` = `blends`.`questionLink`) AS `secondPart`
FROM `blends`
JOIN `accesses` ON `accesses`.`fileId` = `blends`.`id`
GROUP BY `blends`.`id`
HAVING `firstPart` > `secondPart`");

$autoFlag->execute();
$autoFlag = $autoFlag->fetchAll(PDO::FETCH_ASSOC);

foreach ($autoFlag as $key => $aflag)
{
    $autoFlag[$key]["val"] = 'auto-ref';
}


//var_dump($autoFlag);

//Query created with the help of TehShrike http://stackoverflow.com/users/201789/tehshrike
$files = $db->prepare("SELECT `blends`.`id`,`blends`.`fileName`,`blends`.`questionLink`,`accesses`.`val` FROM `blends` JOIN `accesses` ON `accesses`.`fileId` = `blends`.`id` AND `accesses`.`type` = 'flag'");

$files->execute();
$files = $files->fetchAll(PDO::FETCH_ASSOC);

//add autoFlag catches
$files = $autoFlag + $files;

//var_dump($files);
?>