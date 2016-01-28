<?php

//Step one: get data

require_once("../parts/database.php");

require_once("../parts/DynamicAd.php");

$communityAdTwo = new DynamicAd(
        0,
        368,
       'center',
       [
        'size' => 36,
        'color' => [
            'red' => 141,
            'blue' => 141,
            'green' => 141,
        ]
       ],
       "DynamicAdTwo",
       function ( )
       {
                global $db;
                $count = $db->prepare("SELECT COUNT(`blends`.`id`) as blendCount FROM `blends` WHERE `blends`.`deleted` = 0");
                $count->execute();
                $count = $count->fetchAll(PDO::FETCH_ASSOC);
                return $count[0]["blendCount"];
       },
       false
    );

header('Content-Type: image/png');

echo imagepng($communityAdTwo->drawAdd());

?>