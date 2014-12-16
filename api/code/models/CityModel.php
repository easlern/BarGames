<?php
	class City{
		private $ID;
		private $Name;
		private $State;
		private $Country;
		private $Longitude;
		private $Latitude;
		private $CREATEDATE;
		private $LASTMODIFIEDDATE;

		function __construct ($ID, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE){
			$this->ID = $ID;
			$this->Name = $Name;
			$this->State = $State;
			$this->Country = $Country;
			$this->Longitude = $Longitude;
			$this->Latitude = $Latitude;
			$this->CREATEDATE = $CREATEDATE;
			$this->LASTMODIFIEDDATE = $LASTMODIFIEDDATE;
		}

		public function getID(){
			return $this->ID;
		}
		public function getName(){
			return $this->Name;
		}
		public function getState(){
			return $this->State;
		}
		public function getCountry(){
			return $this->Country;
		}
		public function getLongitude(){
			return $this->Longitude;
		}
		public function getLatitude(){
			return $this->Latitude;
		}
		public function getCREATEDATE(){
			return $this->CREATEDATE;
		}
		public function getLASTMODIFIEDDATE(){
			return $this->LASTMODIFIEDDATE;
		}

		public function setID($value){
			$this->ID = $value;
		}
		public function setName($value){
			$this->Name = $value;
		}
		public function setState($value){
			$this->State = $value;
		}
		public function setCountry($value){
			$this->Country = $value;
		}
		public function setLongitude($value){
			$this->Longitude = $value;
		}
		public function setLatitude($value){
			$this->Latitude = $value;
		}
		public function setCREATEDATE($value){
			$this->CREATEDATE = $value;
		}
		public function setLASTMODIFIEDDATE($value){
			$this->LASTMODIFIEDDATE = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->ID = $this->getID();
			$std->Name = $this->getName();
			$std->State = $this->getState();
			$std->Country = $this->getCountry();
			$std->Longitude = $this->getLongitude();
			$std->Latitude = $this->getLatitude();
			$std->CREATEDATE = $this->getCREATEDATE();
			$std->LASTMODIFIEDDATE = $this->getLASTMODIFIEDDATE();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
