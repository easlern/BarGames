<?php
    abstract class AuthorizationMethod{
        const None =        0;
        const Facebook =    1;
        const Google =      2;
        const Twitter =     3;
    }
    
    abstract class AuthorizationLevel{
        const None =        0;
        const User =        1;
        const Admin =       2;
    }

    class Authorization{
        private static $AUTHORIZATION_SESSION_KEY = "AUTHORIZATION";
        
        private $login;
        private $created;
        private $method = AuthorizationMethod::Facebook;
        private $level = AuthorizationLevel::None;
        
        function __construct($method, $login, $level){
            $this->created = new DateTime();
            $this->method = AuthorizationMethod::None;
            $this->login = $login;
            $this->level = $level;
        }
        
        public function getLogin(){
            return $this->login;
        }
        public function getLevel(){
            return $this->level;
        }
        
        public static function isSessionAuthorized(){
            return isset($_SESSION[Authorization::$AUTHORIZATION_SESSION_KEY]);
        }
        public static function setSessionAuthorization(Authorization $auth){
            $_SESSION[Authorization::$AUTHORIZATION_SESSION_KEY] = $auth;
        }
        public static function getSessionAuthorization(){
            if (Authorization::isSessionAuthorized()){
                return $_SESSION[Authorization::$AUTHORIZATION_SESSION_KEY];
            }
            return NULL;
        }
    }
?>
