<?php
    
    require_once ("../startup.php");
    require_once ("database.php");
    require_once ("utils.php");

    if (IsAdminAuthorized()){
        $db = new Database();
        $db->initialize();
        
        header ("html/1.1 204 No content");
    }
    else{
        header ("html/1.1 404 Not found");
    }
    
?>
