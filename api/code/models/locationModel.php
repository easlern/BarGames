<?php
	class Location{
		private $id;
		private $name;
		private $street;
		private $cityId;
		private $phone;
		private $locationTypeIds;
		private $sportIds;

		function __construct ($id, $name, $street, $cityId, $phone, $locationTypeIds, $sportIds){
			$this->id = $id;
			$this->name = $name;
			$this->street = $street;
			$this->cityId = $cityId;
			$this->phone = $phone;
			$this->locationTypeIds = $locationTypeIds;
			$this->sportIds = $sportIds;
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
		public function getSportIds(){
			return $this->sportIds;
		}

		public function setId($value){
			$this->id = $value;
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
		public function setSportIds($value){
			$this->sportIds = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->id = $this->getId();
			$std->name = $this->getName();
			$std->street = $this->getStreet();
			$std->cityId = $this->getCityId();
			$std->phone = $this->getPhone();
			$std->locationTypeIds = $this->getLocationTypeIds();
			$std->sportIds = $this->getSportIds();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
