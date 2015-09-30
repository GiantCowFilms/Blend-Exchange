<html>
    <?php 
    $requireAdmin = false;
    include("../parts/requireLogin.php");
    $pageUser = $userId;
    header('Location: /users/'.$pageUser.'/');
    ?>
    <?php include("../parts/header.php"); ?>
    <a href="//users/<?php echo $pageUser ?>/">Go Here>></a>