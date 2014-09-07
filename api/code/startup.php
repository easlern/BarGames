<?php
    if (session_id() == '') session_start();

    set_include_path(get_include_path() . PATH_SEPARATOR . "/home/indiegam/public_html/BarGames/api/code/");
    set_include_path(get_include_path() . PATH_SEPARATOR . "/home/indiegam/public_html/BarGames/api/code/objects/");
    set_include_path(get_include_path() . PATH_SEPARATOR . "/home/indiegam/public_html/BarGames/api/code/models/");
    set_include_path(get_include_path() . PATH_SEPARATOR . "/home/indiegam/public_html/BarGames/api/code/controllers/");

    require_once ("utils.php");

    error_reporting(0);
    ini_set("display_errors",0);
    if (Utils::GetMode() == Utils::MODE_DEV){
        error_reporting(E_ALL);
        ini_set("display_errors",1);
    }

    require_once("log.php");
    require_once("Authorization.php");
    
    if ($_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR']){
        header('Access-Control-Allow-Origin: https://www.indiegamesurf.com');
    }
    else{
        header('Access-Control-Allow-Origin: http://localhost:8383');
    }
    header('Access-Control-Allow-Credentials: true');
    
?>