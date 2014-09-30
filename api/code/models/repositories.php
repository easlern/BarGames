<?php


	class MySqlGameRepository{
	}

	class TestGameRepository{
		public function getGameById ($id){
			return new Game($id, 0, "test_name", array(0,1,2), 0, array(0,1,2));
		}
	}

	class MySqlSportRepository{
	}

	class TestSportRepository{
		public function getSportById ($id){
			return new Sport($id, "test_name");
		}
	}

	class MySqlTeamRepository{
	}

	class TestTeamRepository{
		public function getTeamById ($id){
			return new Team($id, "test_name");
		}
	}

	class MySqlLocationRepository{
	}

	class TestLocationRepository{
		public function getLocationById ($id){
			return new Location($id, "test_name", "test_street", 0, "test_phone", array(0,1,2));
		}
	}

	class MySqlLocationTypeRepository{
	}

	class TestLocationTypeRepository{
		public function getLocationTypeById ($id){
			return new LocationType($id, "test_name");
		}
	}

	class MySqlTagRepository{
	}

	class TestTagRepository{
		public function getTagById ($id){
			return new Tag($id, "test_name");
		}
	}

	class MySqlUserRepository{
	}

	class TestUserRepository{
		public function getUserById ($id){
			return new User($id, 0, 0, "test_passHash", "test_nameFirst", "test_nameLast", 0);
		}
	}

	class MySqlSecurityLevelRepository{
	}

	class TestSecurityLevelRepository{
		public function getSecurityLevelById ($id){
			return new SecurityLevel($id, "test_name");
		}
	}

	class MySqlCityRepository{
	}

	class TestCityRepository{
		public function getCityById ($id){
			return new City($id, "test_name", "test_state", "test_country", 0, 0);
		}
	}

	class MySqlSettingRepository{
	}

	class TestSettingRepository{
		public function getSettingById ($id){
			return new Setting($id, "test_name", "test_defaultValue");
		}
	}

	class MySqlUserSettingRepository{
	}

	class TestUserSettingRepository{
		public function getUserSettingById ($id){
			return new UserSetting($id, 0, 0, "test_value");
		}
	}

	class Repositories{
		public static function getGameRepository(){
			return new MySqlGameRepository();
		}
		public static function getSportRepository(){
			return new MySqlSportRepository();
		}
		public static function getTeamRepository(){
			return new MySqlTeamRepository();
		}
		public static function getLocationRepository(){
			return new MySqlLocationRepository();
		}
		public static function getLocationTypeRepository(){
			return new MySqlLocationTypeRepository();
		}
		public static function getTagRepository(){
			return new MySqlTagRepository();
		}
		public static function getUserRepository(){
			return new MySqlUserRepository();
		}
		public static function getSecurityLevelRepository(){
			return new MySqlSecurityLevelRepository();
		}
		public static function getCityRepository(){
			return new MySqlCityRepository();
		}
		public static function getSettingRepository(){
			return new MySqlSettingRepository();
		}
		public static function getUserSettingRepository(){
			return new MySqlUserSettingRepository();
		}
	}

?>
