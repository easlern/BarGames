<?php
	class Team{
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

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->name = $this->getName();
			return json_encode ($json);
		}
	}
?>
