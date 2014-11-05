<?php
	class City{
		private $id;
		private $name;
		private $state;
		private $country;
		private $longitude;
		private $latitude;

		function __construct ($id, $name, $state, $country, $longitude, $latitude){
			$this->id = $id;
			$this->name = $name;
			$this->state = $state;
			$this->country = $country;
			$this->longitude = $longitude;
			$this->latitude = $latitude;
		}

		public function getId(){
			return $this->id;
		}
		public function getName(){
			return $this->name;
		}
		public function getState(){
			return $this->state;
		}
		public function getCountry(){
			return $this->country;
		}
		public function getLongitude(){
			return $this->longitude;
		}
		public function getLatitude(){
			return $this->latitude;
		}

		public function setId($value){
			$this->id = $value;
		}
		public function setName($value){
			$this->name = $value;
		}
		public function setState($value){
			$this->state = $value;
		}
		public function setCountry($value){
			$this->country = $value;
		}
		public function setLongitude($value){
			$this->longitude = $value;
		}
		public function setLatitude($value){
			$this->latitude = $value;
		}

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->name = $this->getName();
			$json->state = $this->getState();
			$json->country = $this->getCountry();
			$json->longitude = $this->getLongitude();
			$json->latitude = $this->getLatitude();
			return json_encode ($json);
		}
	}
?>
