<?php
	class Setting{
		private $id;
		private $name;
		private $defaultValue;

		function __construct ($id, $name, $defaultValue){
			$this->id = $id;
			$this->name = $name;
			$this->defaultValue = $defaultValue;
		}

		public function getId(){
			return $this->id;
		}
		public function getName(){
			return $this->name;
		}
		public function getDefaultValue(){
			return $this->defaultValue;
		}

		public function setId($value){
			$this->id = $value;
		}
		public function setName($value){
			$this->name = $value;
		}
		public function setDefaultValue($value){
			$this->defaultValue = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->id = $this->getId();
			$std->name = $this->getName();
			$std->defaultValue = $this->getDefaultValue();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
