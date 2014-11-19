<?php

	require_once ("startup.php");
	require_once ("initializeDb.php");


	class MySqlGameRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into game (locationId, name, sportId) values (?, ?, ?)");
			$statement->bind_param ("isi", $model->getLocationId(), $model->getName(), $model->getSportId());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, locationId, name, sportId from game where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, locationId, name, sportId from game");
			$statement->execute();

			$id = 0;
			$locationId = 0;
			$name = "";
			$sportId = 0;
			$statement->bind_result ($id, $locationId, $name, $sportId);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Game ($id, $locationId, $name, $sportId);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestGameRepository{
		public function getById ($id){
			return new Game($id, 0, "test_name", array(0,1,2), 0, array(0,1,2));
		}
	}

	class MySqlSportRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into sport (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from sport where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name from sport");
			$statement->execute();

			$id = 0;
			$name = "";
			$statement->bind_result ($id, $name);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Sport ($id, $name);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestSportRepository{
		public function getById ($id){
			return new Sport($id, "test_name");
		}
	}

	class MySqlTeamRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into team (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from team where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name from team");
			$statement->execute();

			$id = 0;
			$name = "";
			$statement->bind_result ($id, $name);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Team ($id, $name);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestTeamRepository{
		public function getById ($id){
			return new Team($id, "test_name");
		}
	}

	class MySqlLocationRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into location (name, street, cityId, phone) values (?, ?, ?, ?)");
			$statement->bind_param ("ssis", $model->getName(), $model->getStreet(), $model->getCityId(), $model->getPhone());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, street, cityId, phone from location where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name, street, cityId, phone from location");
			$statement->execute();

			$id = 0;
			$name = "";
			$street = "";
			$cityId = 0;
			$phone = "";
			$statement->bind_result ($id, $name, $street, $cityId, $phone);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Location ($id, $name, $street, $cityId, $phone);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestLocationRepository{
		public function getById ($id){
			return new Location($id, "test_name", "test_street", 0, "test_phone", array(0,1,2));
		}
	}

	class MySqlLocationTypeRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into locationType (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from locationType where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name from locationType");
			$statement->execute();

			$id = 0;
			$name = "";
			$statement->bind_result ($id, $name);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new LocationType ($id, $name);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestLocationTypeRepository{
		public function getById ($id){
			return new LocationType($id, "test_name");
		}
	}

	class MySqlTagRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into tag (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from tag where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name from tag");
			$statement->execute();

			$id = 0;
			$name = "";
			$statement->bind_result ($id, $name);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Tag ($id, $name);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestTagRepository{
		public function getById ($id){
			return new Tag($id, "test_name");
		}
	}

	class MySqlUserRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into user (type, method, passHash, nameFirst, nameLast, securityLevelId) values (?, ?, ?, ?, ?, ?)");
			$statement->bind_param ("iisssi", $model->getType(), $model->getMethod(), $model->getPassHash(), $model->getNameFirst(), $model->getNameLast(), $model->getSecurityLevelId());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, type, method, passHash, nameFirst, nameLast, securityLevelId from user where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, type, method, passHash, nameFirst, nameLast, securityLevelId from user");
			$statement->execute();

			$email = "";
			$type = 0;
			$method = 0;
			$passHash = "";
			$nameFirst = "";
			$nameLast = "";
			$securityLevelId = 0;
			$statement->bind_result ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new User ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestUserRepository{
		public function getById ($id){
			return new User($id, 0, 0, "test_passHash", "test_nameFirst", "test_nameLast", 0);
		}
	}

	class MySqlSecurityLevelRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into securityLevel (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name from securityLevel where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name from securityLevel");
			$statement->execute();

			$id = 0;
			$name = "";
			$statement->bind_result ($id, $name);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new SecurityLevel ($id, $name);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestSecurityLevelRepository{
		public function getById ($id){
			return new SecurityLevel($id, "test_name");
		}
	}

	class MySqlCityRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into city (name, state, country, longitude, latitude) values (?, ?, ?, ?, ?)");
			$statement->bind_param ("sssff", $model->getName(), $model->getState(), $model->getCountry(), $model->getLongitude(), $model->getLatitude());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, state, country, longitude, latitude from city where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name, state, country, longitude, latitude from city");
			$statement->execute();

			$id = 0;
			$name = "";
			$state = "";
			$country = "";
			$longitude = 0;
			$latitude = 0;
			$statement->bind_result ($id, $name, $state, $country, $longitude, $latitude);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new City ($id, $name, $state, $country, $longitude, $latitude);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestCityRepository{
		public function getById ($id){
			return new City($id, "test_name", "test_state", "test_country", 0, 0);
		}
	}

	class MySqlSettingRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into setting (name, defaultValue) values (?, ?)");
			$statement->bind_param ("ss", $model->getName(), $model->getDefaultValue());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, name, defaultValue from setting where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, name, defaultValue from setting");
			$statement->execute();

			$id = 0;
			$name = "";
			$defaultValue = "";
			$statement->bind_result ($id, $name, $defaultValue);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new Setting ($id, $name, $defaultValue);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestSettingRepository{
		public function getById ($id){
			return new Setting($id, "test_name", "test_defaultValue");
		}
	}

	class MySqlUserSettingRepository{
		public function create ($model){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into userSetting (userId, settingId, value) values (?, ?, ?)");
			$statement->bind_param ("iis", $model->getUserId(), $model->getSettingId(), $model->getValue());
			$statement->execute();
			$model->setId ($conn->insert_id);
		}
		public function getById ($id){
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$result = $conn->prepare ("select id, userId, settingId, value from userSetting where id = ?");
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
		public function getAll(){
			$results = array();
			$conn = connectAsWebUser();
			if (!$conn) return $results;
			$statement = $conn->prepare ("select id, userId, settingId, value from userSetting");
			$statement->execute();

			$userId = 0;
			$settingId = 0;
			$value = "";
			$statement->bind_result ($userId, $settingId, $value);
			$statement->store_result();
			while ($statement->fetch()){
				$model = new UserSetting ($userId, $settingId, $value);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestUserSettingRepository{
		public function getById ($id){
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
