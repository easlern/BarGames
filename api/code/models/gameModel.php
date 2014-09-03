<?php
	class Game{
		private $id;
		private $locationId;
		private $name;
		private $categoryIds;

		function __construct ($id, $locationId, $name, $categoryIds){
			$this->id = $id;
			$this->locationId = $locationId;
			$this->name = $name;
			$this->categoryIds = $categoryIds;
		}

		public function getId(){
			return $this->id;
		}
		public function getLocationId(){
			return $this->locationId;
		}
		public function getName(){
			return $this->name;
		}
		public function getCategoryIds(){
			return $this->categoryIds;
		}

		public function setLocationId($value){
			$this->locationId = $value;
		}
		public function setName($value){
			$this->name = $value;
		}
		public function setCategoryIds($value){
			$this->categoryIds = $value;
		}

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->locationId = $this->getLocationId();
			$json->name = $this->getName();
			$json->categoryIds = $this->getCategoryIds();
			return json_encode ($json);
		}
	}
?>
