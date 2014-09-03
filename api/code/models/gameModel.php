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
		public function getLocationid(){
			return $this->locationId;
		}
		public function getName(){
			return $this->name;
		}
		public function getCategoryids(){
			return $this->categoryIds;
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
