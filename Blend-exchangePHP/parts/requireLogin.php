<?php

if(isset($requireAdmin) == false)
{
    $requireAdmin = false;
};

 if (session_status() == PHP_SESSION_NONE) {
     session_start();
 }
$loggedIn = false;
if((isset($_SESSION["loggedIn"]) == true) && ($_SESSION["loggedIn"] == true)){
    $userId = $_SESSION["userId"] ;
    $loggedIn = $_SESSION["loggedIn"];
} else {
    failCheck( );
}
$admin = false;
if(isset($_SESSION["admin"])){
    if (($requireAdmin == true) && ($_SESSION["admin"] == false)) {
        echo "Not an admin";
        failCheck( );
    } else {
        $admin = $_SESSION["admin"];
    }
} else {
    failCheck( );
}

function failCheck( )
{
	include("head.php");
    //Just add body tags, I want the login form to be more independent
    echo '<body><div id="mainContainer" style="margin: auto;">';
    include("loginForm.php");
    echo "</div>";
    exit();
}

?>