<?php
	class Location{
		private $id;
		private $name;
		private $street;
		private $cityId;
		private $phone;
		private $locationTypeIds;

		function __construct ($id, $name, $street, $cityId, $phone, $locationTypeIds){
			$this->id = $id;
			$this->name = $name;
			$this->street = $street;
			$this->cityId = $cityId;
			$this->phone = $phone;
			$this->locationTypeIds = $locationTypeIds;
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
		public function getCityId(){
			return $this->cityId;
		}
		public function getPhone(){
			return $this->phone;
		}
		public function getLocationTypeIds(){
			return $this->locationTypeIds;
		}

		public function setName($value){
			$this->name = $value;
		}
		public function setStreet($value){
			$this->street = $value;
		}
		public function setCityId($value){
			$this->cityId = $value;
		}
		public function setPhone($value){
			$this->phone = $value;
		}
		public function setLocationTypeIds($value){
			$this->locationTypeIds = $value;
		}

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->name = $this->getName();
			$json->street = $this->getStreet();
			$json->cityId = $this->getCityId();
			$json->phone = $this->getPhone();
			$json->locationTypeIds = $this->getLocationTypeIds();
			return json_encode ($json);
		}
	}
?>
