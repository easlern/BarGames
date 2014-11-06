<?php
	class Game{
		private $id;
		private $locationId;
		private $name;
		private $tagIds;
		private $sportId;
		private $teamIds;

		function __construct ($id, $locationId, $name, $tagIds, $sportId, $teamIds){
			$this->id = $id;
			$this->locationId = $locationId;
			$this->name = $name;
			$this->tagIds = $tagIds;
			$this->sportId = $sportId;
			$this->teamIds = $teamIds;
		}

		public function getId(){
			return $this->id;
		}
		public function getLocationId(){
			return $this->locationId;
		}
		public function getName(){
			return $this->name;
		}
		public function getTagIds(){
			return $this->tagIds;
		}
		public function getSportId(){
			return $this->sportId;
		}
		public function getTeamIds(){
			return $this->teamIds;
		}

		public function setId($value){
			$this->id = $value;
		}
		public function setLocationId($value){
			$this->locationId = $value;
		}
		public function setName($value){
			$this->name = $value;
		}
		public function setTagIds($value){
			$this->tagIds = $value;
		}
		public function setSportId($value){
			$this->sportId = $value;
		}
		public function setTeamIds($value){
			$this->teamIds = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->id = $this->getId();
			$std->locationId = $this->getLocationId();
			$std->name = $this->getName();
			$std->tagIds = $this->getTagIds();
			$std->sportId = $this->getSportId();
			$std->teamIds = $this->getTeamIds();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
