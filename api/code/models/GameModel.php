<?php
	class Game{
		private $id;
		private $locationId;
		private $name;
		private $categoryIds;

		public function getId(){
			return $this->id;
		}
		public function getLocationid(){
			return $this->locationId;
		}
		public function getName(){
			return $this->name;
		}
		public function getCategoryids(){
			return $this->categoryIds;
		}

		public function setId($value){
			$this->id = $value;
		}
		public function setLocationid($value){
			$this->locationId = $value;
		}
		public function setName($value){
			$this->name = $value;
		}
		public function setCategoryids($value){
			$this->categoryIds = $value;
		}
	}
?>
