<?php

session_start();

if((isset($_SESSION["loggedIn"]) == true) && ($_SESSION["loggedIn"] == true)){
    $userId = $_SESSION["userId"] ;
} else {
    include("head.php");
    //Just add body tags, I want the login form to be more independent
    echo '<body><div id="mainContainer" style="margin: auto;">';
    include("loginForm.php");
    echo "</div><body/>";
    exit();
};

?>