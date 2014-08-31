<?php
	class user{
		private $login;
		private $type;
		private $method;

		public function getLogin(){
			return $this->login;
		}
		public function getType(){
			return $this->type;
		}
		public function getMethod(){
			return $this->method;
		}

		public function setLogin($value){
			$this->login = $value;
		}
		public function setType($value){
			$this->type = $value;
		}
		public function setMethod($value){
			$this->method = $value;
		}
	}
?>
