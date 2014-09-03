<?php
	class User{
		private $login;
		private $type;
		private $method;

		function __construct ($login, $type, $method){
			$this->login = $login;
			$this->type = $type;
			$this->method = $method;
		}

		public function getLogin(){
			return $this->login;
		}
		public function getType(){
			return $this->type;
		}
		public function getMethod(){
			return $this->method;
		}

		public function setType($value){
			$this->type = $value;
		}
		public function setMethod($value){
			$this->method = $value;
		}
	}
?>
