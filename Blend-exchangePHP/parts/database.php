<?php 
$databaseCreds = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/secret/secret.json"))->mysql;

$db = new PDO("mysql:host=".$databaseCreds->host.";dbname=".$databaseCreds->database,$databaseCreds->user,$databaseCreds->password);

$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>