<?php
    header ('Content-Type: application/json');
    
    class ApiErrorResponse{
        public $status = "ERROR";
        public $message;
        
        function __construct ($message){
            $this->message = $message;
        }
    }
?>
