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

		public function setName($value){
			$this->name = $value;
		}
		public function setDefaultValue($value){
			$this->defaultValue = $value;
		}

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->name = $this->getName();
			$json->defaultValue = $this->getDefaultValue();
			return json_encode ($json);
		}
	}
?>
