<?php
	class User{
		private $email;
		private $type;
		private $method;
		private $passHash;
		private $nameFirst;
		private $nameLast;
		private $securityLevelId;

		function __construct ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId){
			$this->email = $email;
			$this->type = $type;
			$this->method = $method;
			$this->passHash = $passHash;
			$this->nameFirst = $nameFirst;
			$this->nameLast = $nameLast;
			$this->securityLevelId = $securityLevelId;
		}

		public function getEmail(){
			return $this->email;
		}
		public function getType(){
			return $this->type;
		}
		public function getMethod(){
			return $this->method;
		}
		public function getPassHash(){
			return $this->passHash;
		}
		public function getNameFirst(){
			return $this->nameFirst;
		}
		public function getNameLast(){
			return $this->nameLast;
		}
		public function getSecurityLevelId(){
			return $this->securityLevelId;
		}

		public function setEmail($value){
			$this->email = $value;
		}
		public function setType($value){
			$this->type = $value;
		}
		public function setMethod($value){
			$this->method = $value;
		}
		public function setPassHash($value){
			$this->passHash = $value;
		}
		public function setNameFirst($value){
			$this->nameFirst = $value;
		}
		public function setNameLast($value){
			$this->nameLast = $value;
		}
		public function setSecurityLevelId($value){
			$this->securityLevelId = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->email = $this->getEmail();
			$std->type = $this->getType();
			$std->method = $this->getMethod();
			$std->passHash = $this->getPassHash();
			$std->nameFirst = $this->getNameFirst();
			$std->nameLast = $this->getNameLast();
			$std->securityLevelId = $this->getSecurityLevelId();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
