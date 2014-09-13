<?php

function LogInfo($what){
    #print ("in " . getcwd());
    $filename = "/home/indiegam/barGamesLog.txt";
    if (Utils::GetMode() == Utils::MODE_PROD) $filename = "/home/easlern/barGamesLog.txt";
    $file = fopen($filename, "a");
    fwrite($file, date("Y-m-d H:i:s") . ": " . $what . "\r\n");
    fclose($file);
}

?>