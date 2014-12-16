<?php
	class UserProfile{
		private $ID;
		private $UserID;
		private $AboutMe;
		private $Interests;
		private $CREATEDATE;
		private $LASTMODIFIEDDATE;

		function __construct ($ID, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE){
			$this->ID = $ID;
			$this->UserID = $UserID;
			$this->AboutMe = $AboutMe;
			$this->Interests = $Interests;
			$this->CREATEDATE = $CREATEDATE;
			$this->LASTMODIFIEDDATE = $LASTMODIFIEDDATE;
		}

		public function getID(){
			return $this->ID;
		}
		public function getUserID(){
			return $this->UserID;
		}
		public function getAboutMe(){
			return $this->AboutMe;
		}
		public function getInterests(){
			return $this->Interests;
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
		public function setUserID($value){
			$this->UserID = $value;
		}
		public function setAboutMe($value){
			$this->AboutMe = $value;
		}
		public function setInterests($value){
			$this->Interests = $value;
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
			$std->UserID = $this->getUserID();
			$std->AboutMe = $this->getAboutMe();
			$std->Interests = $this->getInterests();
			$std->CREATEDATE = $this->getCREATEDATE();
			$std->LASTMODIFIEDDATE = $this->getLASTMODIFIEDDATE();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
