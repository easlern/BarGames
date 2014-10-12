<?php
    if (session_id() == '') session_start();

    $baseDir = "/var/www/expirednews.net";
    set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir . "/api/code/");
    set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir . "/api/code/objects/");
    set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir . "/api/code/models/");
    set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir . "/api/code/controllers/");
    set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir . "/api/code/data/");

    require_once ("utils.php");

    error_reporting(E_ALL);
    ini_set("display_errors",0);
    if (Utils::GetMode() == Utils::MODE_DEV){
        error_reporting(E_ALL);
        ini_set("display_errors",1);
    }

    require_once("log.php");
    
    header('Access-Control-Allow-Credentials: true');
    
?>