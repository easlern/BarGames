<?php
	class Category{
		private $id;
		private $name;

		function __construct ($id, $name){
			$this->id = $id;
			$this->name = $name;
		}

		public function getId(){
			return $this->id;
		}
		public function getName(){
			return $this->name;
		}

		public function setName($value){
			$this->name = $value;
		}
	}
?>
