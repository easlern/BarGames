<?php

	class TestGameRepository{
		public function getGameById ($id){
			return new Game($id, 0, "test_name", array(0,1,2), 0, array(0,1,2));
		}
	}
	class TestSportRepository{
		public function getSportById ($id){
			return new Sport($id, "test_name");
		}
	}
	class TestTeamRepository{
		public function getTeamById ($id){
			return new Team($id, "test_name");
		}
	}
	class TestLocationRepository{
		public function getLocationById ($id){
			return new Location($id, "test_name", "test_street", 0, "test_phone", array(0,1,2));
		}
	}
	class TestLocationTypeRepository{
		public function getLocationTypeById ($id){
			return new LocationType($id, "test_name");
		}
	}
	class TestTagRepository{
		public function getTagById ($id){
			return new Tag($id, "test_name");
		}
	}
	class TestUserRepository{
		public function getUserById ($id){
			return new User($id, 0, 0, "test_passHash", "test_nameFirst", "test_nameLast", 0);
		}
	}
	class TestSecurityLevelRepository{
		public function getSecurityLevelById ($id){
			return new SecurityLevel($id, "test_name");
		}
	}
	class TestCityRepository{
		public function getCityById ($id){
			return new City($id, "test_name", "test_state", "test_country", 0, 0);
		}
	}
	class TestSettingRepository{
		public function getSettingById ($id){
			return new Setting($id, "test_name", "test_defaultValue");
		}
	}
	class TestUserSettingRepository{
		public function getUserSettingById ($id){
			return new UserSetting($id, 0, 0, "test_value");
		}
	}

	class Repositories{
		public static function getGameRepository(){
			return new TestGameRepository();
		}
		public static function getSportRepository(){
			return new TestSportRepository();
		}
		public static function getTeamRepository(){
			return new TestTeamRepository();
		}
		public static function getLocationRepository(){
			return new TestLocationRepository();
		}
		public static function getLocationTypeRepository(){
			return new TestLocationTypeRepository();
		}
		public static function getTagRepository(){
			return new TestTagRepository();
		}
		public static function getUserRepository(){
			return new TestUserRepository();
		}
		public static function getSecurityLevelRepository(){
			return new TestSecurityLevelRepository();
		}
		public static function getCityRepository(){
			return new TestCityRepository();
		}
		public static function getSettingRepository(){
			return new TestSettingRepository();
		}
		public static function getUserSettingRepository(){
			return new TestUserSettingRepository();
		}
	}

?>
