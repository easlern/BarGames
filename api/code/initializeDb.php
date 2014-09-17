<?php

function connectAsAdmin(){
    if (isset($_GET["18973489710982739847981723473219487"])){
        if (Utils::GetMode() == Utils::MODE_DEV){
            $con = new mysqli("localhost", "indiegam_bgadmin", "tohnsetadoedu19832740987");
            $con->query("use indiegam_bargames;");
        }
        else{
            $con = new mysqli("localhost", "easlern_bgadmin", "bg4dm1n");
            $con->query("use easlern_bargames;");
        }
        return $con;
    }
}
function connectAsWebUser(){
    $con = NULL;
    
    if (Utils::GetMode() == Utils::MODE_DEV){
        $con = new mysqli("localhost", "indiegam_bguser", "ntahoeu78917234");
        $con->query("use indiegam_bargames;");
    }
    else{
        //$con = new mysqli("localhost", "root", "\$umm3rt1m3");
        $con = new mysqli("localhost", "easlern_bguser", "bgus3r"); 
        $con->query("use easlern_bargames;");
    }
    return $con;
}

?>