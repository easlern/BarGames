<?php

const AUTH_FAILURE = 1;
const BLACKLISTED = 4;
const GENERAL_FAULT = 5;
const INVALID_PARAMS = 6;
const SUCCESS = 0;


function GetCsrfToken(){
    if (!isset($_SESSION['CSRF_TOKEN'])) $_SESSION['CSRF_TOKEN'] = SafeRandomNumberString(10);
    return $_SESSION['CSRF_TOKEN'];
}


function SanitizeStringArray ($arr){
    $vars = array();
    foreach ($arr as $key=>$val){
        if (strlen ($key) < 1024 && strlen ($val) < 1024) $vars [$key] = SanitizePlainText ($val);
    }
    return $vars;
}
function GetSanitizedGetVars(){
    return SanitizeStringArray($_GET);
}
function GetSanitizedPostVars(){
    return SanitizeStringArray($_POST);
}
function GetSanitizedPutVars(){
    parse_str (file_get_contents ("php://input"), $putVars);
    
    return SanitizeStringArray($putVars);
}
function GetSanitizedDeleteVars(){
    parse_str (file_get_contents ("php://input"), $deleteVars);
    
    return SanitizeStringArray($deleteVars);
}

function SanitizePlainText($text){
    $safeChars = "0123456789 +=_@.,'?!_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/()\r\n";
    $sanitizedText = "";

    for($i=0;$i<strlen($text);$i++) 
    { 
        if (strpos($safeChars, $text[$i]) !== FALSE){
            $sanitizedText .= $text[$i];
        } 
    } 

    return $sanitizedText;
}

function IsAuthorized(){
    return true;
}
function IsAdminAuthorized(){
    return true;
}
function IsCsrfGood(){
    $goodCsrf = false;
    
    if (isset($_POST["csrfToken"]) && SanitizePlainText($_POST["csrfToken"]) == GetCsrfToken()) $goodCsrf = true;
    if (Utils::GetMode() == Utils::MODE_DEV && isset($_GET["csrfTokenOverride"])) $goodCsrf = true;
    return $goodCsrf;
}
function IsBlocked(){
    $ip = $_SERVER["REMOTE_ADDR"];
    $connection = Connections::GetConnection($ip);
    if ($connection == null){
        LogInfo("utils.php: IP $ip connection was not found in the database.");
        return false;
    }
    
    return $connection->GetBlocked() != 0;
}

function SafeRandomNumberString($length){
    $numberString = "";
    $fp = @fopen('/dev/urandom','rb');
    if ($fp !== FALSE) {
        for ($x = 0; $x < $length; $x++){
            $numberString .= ord(@fread($fp,1)) % 10;
        }
        @fclose($fp);
    }

    return $numberString;
}

class Utils{
    const MODE_DEV = "dev";
    const MODE_PROD = "prod";
    
    static function GetMode(){
        if (Utils::GetDomain() === "www.indiegamesurf.com") return Utils::MODE_DEV;

        return Utils::MODE_PROD;
    }

    static function GetDomain(){
        if (isset($_SERVER["HTTP_HOST"])){
            return $_SERVER["HTTP_HOST"];
        }
        return "www.echosomething.com";
    }
}

?>
