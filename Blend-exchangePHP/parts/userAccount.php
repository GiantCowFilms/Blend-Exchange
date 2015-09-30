<?php 
include($_SERVER["DOCUMENT_ROOT"]."/parts/checkLogin.php");
include($_SERVER["DOCUMENT_ROOT"]."/parts/database.php");

//Query created with the help of TehShrike http://stackoverflow.com/users/201789/tehshrike
$files = $db->prepare("SELECT `blends`.`id`,`blends`.`fileName`,`blends`.`questionLink`,`blends`.`date` FROM `blends` 
WHERE `blends`.`deleted`= 0 AND `blends`.`owner`=:uid
");

$files->execute(["uid" => $pageUser]);
$files = $files->fetchAll(PDO::FETCH_ASSOC);

include($_SERVER["DOCUMENT_ROOT"]."/parts/blendViewCount.php");

foreach ($files as $key => $file)
{
    $files[$key]["views"] = getViewCount($file["id"]);
}


//add autoFlag catches
?>
<h2>Your Blends</h2>
<table>
    <thead>
        <tr>
            <th>File Name</th><th>Question</th><th>views</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php

        foreach ($files as $file)
        {
            echo "<tr><td><a href='/b/".$file["id"]."/'>".$file["fileName"]."</a></td>
            <td><a href='".$file["questionLink"]."'>".substr($file["questionLink"], 32, 60)."...</a></td>
            <td>".$file["views"]."</td><td>".$file["date"]."</td></tr>";
        }
        ?>
    </tbody>
</table>

    <h2>Your Account</h2>

    <?php
    if($pageUser == $userId){
        include($_SERVER["DOCUMENT_ROOT"]."/parts/editAccountForm.php");  
    };  
        
    ?>