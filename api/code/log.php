<?php

function LogInfo($what){
    #print ("in " . getcwd());
    $filename = "/var/log/barGamesLog.txt";
    if (Utils::GetMode() == Utils::MODE_PROD) $filename = "/var/log/barGamesLog.txt";
    $file = fopen($filename, "a");
    fwrite($file, date("Y-m-d H:i:s") . ": " . $what . "\r\n");
    fclose($file);
}

?>