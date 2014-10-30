<?php

	require_once ("../startup.php");
	require_once ("initializeDb.php");


	class MySqlGameRepository{
		public function getGameById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, locationId, name, sportId from Game where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$locationId = 0;
			$name = "";
			$sportId = 0;
			$result->bind_result ($id, $locationId, $name, $sportId);
			if ($result->fetch()){
				return new Game ($id, $locationId, $name, $sportId);
			}
			return NULL;
		}

	}

	class TestGameRepository{
		public function getGameById ($id){
			return new Game($id, 0, "test_name", array(0,1,2), 0, array(0,1,2));
		}
	}

	class MySqlSportRepository{
		public function getSportById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from Sport where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$result->bind_result ($id, $name);
			if ($result->fetch()){
				return new Sport ($id, $name);
			}
			return NULL;
		}

	}

	class TestSportRepository{
		public function getSportById ($id){
			return new Sport($id, "test_name");
		}
	}

	class MySqlTeamRepository{
		public function getTeamById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from Team where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$result->bind_result ($id, $name);
			if ($result->fetch()){
				return new Team ($id, $name);
			}
			return NULL;
		}

	}

	class TestTeamRepository{
		public function getTeamById ($id){
			return new Team($id, "test_name");
		}
	}

	class MySqlLocationRepository{
		public function getLocationById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, street, cityId, phone from Location where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$street = "";
			$cityId = 0;
			$phone = "";
			$result->bind_result ($id, $name, $street, $cityId, $phone);
			if ($result->fetch()){
				return new Location ($id, $name, $street, $cityId, $phone);
			}
			return NULL;
		}

	}

	class TestLocationRepository{
		public function getLocationById ($id){
			return new Location($id, "test_name", "test_street", 0, "test_phone", array(0,1,2));
		}
	}

	class MySqlLocationTypeRepository{
		public function getLocationTypeById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from LocationType where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$result->bind_result ($id, $name);
			if ($result->fetch()){
				return new LocationType ($id, $name);
			}
			return NULL;
		}

	}

	class TestLocationTypeRepository{
		public function getLocationTypeById ($id){
			return new LocationType($id, "test_name");
		}
	}

	class MySqlTagRepository{
		public function getTagById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from Tag where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$result->bind_result ($id, $name);
			if ($result->fetch()){
				return new Tag ($id, $name);
			}
			return NULL;
		}

	}

	class TestTagRepository{
		public function getTagById ($id){
			return new Tag($id, "test_name");
		}
	}

	class MySqlUserRepository{
		public function getUserById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, email, type, method, passHash, nameFirst, nameLast, securityLevelId from User where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$email = "";
			$type = 0;
			$method = 0;
			$passHash = "";
			$nameFirst = "";
			$nameLast = "";
			$securityLevelId = 0;
			$result->bind_result ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
			if ($result->fetch()){
				return new User ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
			}
			return NULL;
		}

	}

	class TestUserRepository{
		public function getUserById ($id){
			return new User($id, 0, 0, "test_passHash", "test_nameFirst", "test_nameLast", 0);
		}
	}

	class MySqlSecurityLevelRepository{
		public function getSecurityLevelById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from SecurityLevel where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$result->bind_result ($id, $name);
			if ($result->fetch()){
				return new SecurityLevel ($id, $name);
			}
			return NULL;
		}

	}

	class TestSecurityLevelRepository{
		public function getSecurityLevelById ($id){
			return new SecurityLevel($id, "test_name");
		}
	}

	class MySqlCityRepository{
		public function getCityById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, state, country, longitude, latitude from City where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$state = "";
			$country = "";
			$longitude = 0;
			$latitude = 0;
			$result->bind_result ($id, $name, $state, $country, $longitude, $latitude);
			if ($result->fetch()){
				return new City ($id, $name, $state, $country, $longitude, $latitude);
			}
			return NULL;
		}

	}

	class TestCityRepository{
		public function getCityById ($id){
			return new City($id, "test_name", "test_state", "test_country", 0, 0);
		}
	}

	class MySqlSettingRepository{
		public function getSettingById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, defaultValue from Setting where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$id = 0;
			$name = "";
			$defaultValue = "";
			$result->bind_result ($id, $name, $defaultValue);
			if ($result->fetch()){
				return new Setting ($id, $name, $defaultValue);
			}
			return NULL;
		}

	}

	class TestSettingRepository{
		public function getSettingById ($id){
			return new Setting($id, "test_name", "test_defaultValue");
		}
	}

	class MySqlUserSettingRepository{
		public function getUserSettingById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, userId, settingId, value from UserSetting where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$userId = 0;
			$settingId = 0;
			$value = "";
			$result->bind_result ($userId, $settingId, $value);
			if ($result->fetch()){
				return new UserSetting ($userId, $settingId, $value);
			}
			return NULL;
		}

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
