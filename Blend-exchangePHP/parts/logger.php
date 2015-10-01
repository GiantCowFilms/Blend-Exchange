<?php
function logger($text = "",$path,$file = "log.log", $addTime = true){
    $time = $addTime ? " ON:" . date('Y-m-d H:i:s') : "";
    $logString = $text .  $time . PHP_EOL;
    @file_put_contents($path.$file, $logString, FILE_APPEND | LOCK_EX);

}
?>