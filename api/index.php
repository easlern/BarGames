<?php
    require_once('code/startup.php');
    require_once('restfulSetup.php');
    
    if (strlen($_SERVER['REQUEST_URI']) > 1024) exit();

    $requestURI = explode('/', $_SERVER['REQUEST_URI']);
    $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

    for($i = 0; $i < sizeof ($scriptName); $i++)
    {
        if ($requestURI [$i] == $scriptName [$i])
        {
            unset($requestURI [$i]);
        }
    }

    $command = SanitizeStringArray (array_values ($requestURI));
    $controller = $command [0];
    $args = array_slice ($command, 1);
?>