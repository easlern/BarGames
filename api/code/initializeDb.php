<?php

function connectAsAdmin(){
    if (isset($_GET["18973489710982739847981723473219487"])){
        if (Utils::GetMode() == Utils::MODE_DEV){
            $con = new mysqli("localhost", "bgadmin", "tohnsetadoedu19832740987");
            $con->query("use bargames;");
        }
        else{
            $con = new mysqli("localhost", "bgadmin", "bg4dm1n");
            $con->query("use bargames;");
        }
        return $con;
    }
}
function connectAsWebUser(){
    $con = NULL;
    
    if (Utils::GetMode() == Utils::MODE_DEV){
        $con = new mysqli("localhost", "bguser", "ntahoeu78917234");
        $con->query("use bargames;");
    }
    else{
        //$con = new mysqli("localhost", "root", "\$umm3rt1m3");
        $con = new mysqli("localhost", "bguser", "bgus3r"); 
        $con->query("use bargames;");
    }
    return $con;
}

?>