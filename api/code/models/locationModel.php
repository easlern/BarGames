<?php
	class Location{
		private $id;
		private $name;
		private $street;
		private $city;
		private $state;
		private $phone;

		function __construct ($id, $name, $street, $city, $state, $phone){
			$this->id = $id;
			$this->name = $name;
			$this->street = $street;
			$this->city = $city;
			$this->state = $state;
			$this->phone = $phone;
		}

		public function getId(){
			return $this->id;
		}
		public function getName(){
			return $this->name;
		}
		public function getStreet(){
			return $this->street;
		}
		public function getCity(){
			return $this->city;
		}
		public function getState(){
			return $this->state;
		}
		public function getPhone(){
			return $this->phone;
		}

		public function setName($value){
			$this->name = $value;
		}
		public function setStreet($value){
			$this->street = $value;
		}
		public function setCity($value){
			$this->city = $value;
		}
		public function setState($value){
			$this->state = $value;
		}
		public function setPhone($value){
			$this->phone = $value;
		}

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->name = $this->getName();
			$json->street = $this->getStreet();
			$json->city = $this->getCity();
			$json->state = $this->getState();
			$json->phone = $this->getPhone();
			return json_encode ($json);
		}
	}
?>
