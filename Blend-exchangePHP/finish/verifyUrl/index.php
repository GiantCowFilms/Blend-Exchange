<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/parts/verifyUrl.php");
$url = $_GET["url"];
if(verifyUrl($url,true)){
    echo '{
            "status": 1,
            "message": "Url is valid"
        }';
} else {
    //Send status
    echo '{
            "status": 0,
            "message": "Invalid Url"
        }';
}
?>

