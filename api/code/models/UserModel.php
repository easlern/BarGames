<?php
	class User{
		private $ID;
		private $AccountID;
		private $FirstName;
		private $LastName;
		private $Email;
		private $CityID;
		private $MiddleName;
		private $DisplayName;
		private $Description;
		private $BirthDate;
		private $CREATEDATE;
		private $LASTMODIFIEDDATE;

		function __construct ($ID, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE){
			$this->ID = $ID;
			$this->AccountID = $AccountID;
			$this->FirstName = $FirstName;
			$this->LastName = $LastName;
			$this->Email = $Email;
			$this->CityID = $CityID;
			$this->MiddleName = $MiddleName;
			$this->DisplayName = $DisplayName;
			$this->Description = $Description;
			$this->BirthDate = $BirthDate;
			$this->CREATEDATE = $CREATEDATE;
			$this->LASTMODIFIEDDATE = $LASTMODIFIEDDATE;
		}

		public function getID(){
			return $this->ID;
		}
		public function getAccountID(){
			return $this->AccountID;
		}
		public function getFirstName(){
			return $this->FirstName;
		}
		public function getLastName(){
			return $this->LastName;
		}
		public function getEmail(){
			return $this->Email;
		}
		public function getCityID(){
			return $this->CityID;
		}
		public function getMiddleName(){
			return $this->MiddleName;
		}
		public function getDisplayName(){
			return $this->DisplayName;
		}
		public function getDescription(){
			return $this->Description;
		}
		public function getBirthDate(){
			return $this->BirthDate;
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
		public function setAccountID($value){
			$this->AccountID = $value;
		}
		public function setFirstName($value){
			$this->FirstName = $value;
		}
		public function setLastName($value){
			$this->LastName = $value;
		}
		public function setEmail($value){
			$this->Email = $value;
		}
		public function setCityID($value){
			$this->CityID = $value;
		}
		public function setMiddleName($value){
			$this->MiddleName = $value;
		}
		public function setDisplayName($value){
			$this->DisplayName = $value;
		}
		public function setDescription($value){
			$this->Description = $value;
		}
		public function setBirthDate($value){
			$this->BirthDate = $value;
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
			$std->AccountID = $this->getAccountID();
			$std->FirstName = $this->getFirstName();
			$std->LastName = $this->getLastName();
			$std->Email = $this->getEmail();
			$std->CityID = $this->getCityID();
			$std->MiddleName = $this->getMiddleName();
			$std->DisplayName = $this->getDisplayName();
			$std->Description = $this->getDescription();
			$std->BirthDate = $this->getBirthDate();
			$std->CREATEDATE = $this->getCREATEDATE();
			$std->LASTMODIFIEDDATE = $this->getLASTMODIFIEDDATE();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
