<?php
    require_once ("../code/startup.php");
    require_once ("restfulSetup.php");
    
    require_once("AuthorizationResult.php");
    
    $postVars = GetSanitizedPostVars();
    $login = "";
    if (isset($postVars["login"])) $login = $postVars["login"];
    $level = AuthorizationLevel::None;
    if ($login !== "") $level = AuthorizationLevel::User;
    Authorization::setSessionAuthorization(new Authorization(AuthorizationMethod::Facebook, $login, $level));

    $authorization = Authorization::getSessionAuthorization();
    $result = new AuthorizationResult($authorization->getLogin(), $authorization->getLevel());
    print(json_encode($result));
?>