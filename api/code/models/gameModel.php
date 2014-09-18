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

		public function toJson(){
			$json = new stdClass();
			$json->id = $this->getId();
			$json->locationId = $this->getLocationId();
			$json->name = $this->getName();
			$json->tagIds = $this->getTagIds();
			$json->sportId = $this->getSportId();
			$json->teamIds = $this->getTeamIds();
			return json_encode ($json);
		}
	}
?>
