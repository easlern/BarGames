<?php
	class Account{
		private $ID;
		private $Password;
		private $Username;
		private $AccountPicturePath;
		private $AccountTypeID;
		private $IsDisabled;
		private $LastLoginDate;
		private $CREATEDATE;
		private $LASTMODIFIEDDATE;

		function __construct ($ID, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE){
			$this->ID = $ID;
			$this->Password = $Password;
			$this->Username = $Username;
			$this->AccountPicturePath = $AccountPicturePath;
			$this->AccountTypeID = $AccountTypeID;
			$this->IsDisabled = $IsDisabled;
			$this->LastLoginDate = $LastLoginDate;
			$this->CREATEDATE = $CREATEDATE;
			$this->LASTMODIFIEDDATE = $LASTMODIFIEDDATE;
		}

		public function getID(){
			return $this->ID;
		}
		public function getPassword(){
			return $this->Password;
		}
		public function getUsername(){
			return $this->Username;
		}
		public function getAccountPicturePath(){
			return $this->AccountPicturePath;
		}
		public function getAccountTypeID(){
			return $this->AccountTypeID;
		}
		public function getIsDisabled(){
			return $this->IsDisabled;
		}
		public function getLastLoginDate(){
			return $this->LastLoginDate;
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
		public function setPassword($value){
			$this->Password = $value;
		}
		public function setUsername($value){
			$this->Username = $value;
		}
		public function setAccountPicturePath($value){
			$this->AccountPicturePath = $value;
		}
		public function setAccountTypeID($value){
			$this->AccountTypeID = $value;
		}
		public function setIsDisabled($value){
			$this->IsDisabled = $value;
		}
		public function setLastLoginDate($value){
			$this->LastLoginDate = $value;
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
			$std->Password = $this->getPassword();
			$std->Username = $this->getUsername();
			$std->AccountPicturePath = $this->getAccountPicturePath();
			$std->AccountTypeID = $this->getAccountTypeID();
			$std->IsDisabled = $this->getIsDisabled();
			$std->LastLoginDate = $this->getLastLoginDate();
			$std->CREATEDATE = $this->getCREATEDATE();
			$std->LASTMODIFIEDDATE = $this->getLASTMODIFIEDDATE();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
