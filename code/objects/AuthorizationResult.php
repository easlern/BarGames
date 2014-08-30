<?php
    class AuthorizationResult{
        public $login = "";
        public $level = AuthorizationLevel::None;
        
        function __construct($login, $level){
            $this->login = $login;
            $this->level = $level;
        }
    }
?>
