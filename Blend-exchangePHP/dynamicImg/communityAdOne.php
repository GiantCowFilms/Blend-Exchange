<?php

//Step one: get data

require_once("../parts/database.php");

require_once("../parts/DynamicAd.php");

$communityAddOne = new DynamicAd(
        0,
        445,
       'center',
       [
        'size' => 20,
        'color' => [
            'red' => 141,
            'blue' => 141,
            'green' => 141,
        ]
       ],
       "DynamicCommunityAdOne",
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

echo imagepng($communityAddOne->drawAdd());

?>