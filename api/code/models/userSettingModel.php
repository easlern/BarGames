<?php
	class UserSetting{
		private $userId;
		private $settingId;
		private $value;

		function __construct ($userId, $settingId, $value){
			$this->userId = $userId;
			$this->settingId = $settingId;
			$this->value = $value;
		}

		public function getUserId(){
			return $this->userId;
		}
		public function getSettingId(){
			return $this->settingId;
		}
		public function getValue(){
			return $this->value;
		}

		public function setUserId($value){
			$this->userId = $value;
		}
		public function setSettingId($value){
			$this->settingId = $value;
		}
		public function setValue($value){
			$this->value = $value;
		}

		public function toStdClass(){
			$std = new stdClass();
			$std->userId = $this->getUserId();
			$std->settingId = $this->getSettingId();
			$std->value = $this->getValue();
			return $std;
		}
		public function toJson(){
			return json_encode ($this->toStdClass());
		}
	}
?>
