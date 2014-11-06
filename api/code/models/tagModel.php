<?php
	class Tag{
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

		public function setId($value){
			$this->id = $value;
		}
		public function setName($value){
			$this->name = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->id = $this->getId();
			$std->name = $this->getName();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
