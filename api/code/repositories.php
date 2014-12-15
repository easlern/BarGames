<?php

	require_once ("startup.php");
	require_once ("initializeDb.php");


	class MySqlGameRepository{
		public function create ($model){
			LogInfo ("Creating game");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into game (locationId, name, sportId) values (?, ?, ?)");
			$statement->bind_param ("isi", $model->getLocationId(), $model->getName(), $model->getSportId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			foreach ($model->getTagIds() as $id){
				$statement = $conn->prepare ("insert into mtm_game_tag (gameId, tagId) values (?, ?)");
				$statement->bind_param ("ii", $model->getId(), $id);
				$result = $statement->execute();
				if (!$result){
					LogInfo ("SQL error: " . $statement->error);
				}
			}
			foreach ($model->getTeamIds() as $id){
				$statement = $conn->prepare ("insert into mtm_game_team (gameId, teamId) values (?, ?)");
				$statement->bind_param ("ii", $model->getId(), $id);
				$result = $statement->execute();
				if (!$result){
					LogInfo ("SQL error: " . $statement->error);
				}
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$foreignId = 0;
				$tagIds = array();
				$mtmResult = $mtmConn->prepare ("select tagId from mtm_game_tag where gameId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($tagIds, $foreignId);
				}
				$mtmResult->close();
				$foreignId = 0;
				$teamIds = array();
				$mtmResult = $mtmConn->prepare ("select teamId from mtm_game_team where gameId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($teamIds, $foreignId);
				}
				$mtmResult->close();
				$mtmConn->close();

				return new Game ($id, $locationId, $name, $tagIds, $sportId, $teamIds);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo game with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from game where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update game set locationId = ?, name = ?, sportId = ? where id = ?");
			$statement->bind_param ("isii", $model->getLocationId(), $model->getName(), $model->getSportId(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$foreignId = 0;
				$tagIds = array();
				$mtmResult = $mtmConn->prepare ("select tagId from mtm_game_tag where gameId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($tagIds, $foreignId);
				}
				$mtmResult->close();
				$foreignId = 0;
				$teamIds = array();
				$mtmResult = $mtmConn->prepare ("select teamId from mtm_game_team where gameId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($teamIds, $foreignId);
				}
				$mtmResult->close();
				$mtmConn->close();

				$model = new Game ($id, $locationId, $name, $tagIds, $sportId, $teamIds);
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
			LogInfo ("Creating sport");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into sport (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new Sport ($id, $name);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo sport with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from sport where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update sport set name = ? where id = ?");
			$statement->bind_param ("si", $model->getName(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating team");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into team (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new Team ($id, $name);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo team with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from team where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update team set name = ? where id = ?");
			$statement->bind_param ("si", $model->getName(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating location");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into location (name, street, cityId, phone) values (?, ?, ?, ?)");
			$statement->bind_param ("ssis", $model->getName(), $model->getStreet(), $model->getCityId(), $model->getPhone());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			foreach ($model->getLocationtypeIds() as $id){
				$statement = $conn->prepare ("insert into mtm_location_locationtype (locationId, locationtypeId) values (?, ?)");
				$statement->bind_param ("ii", $model->getId(), $id);
				$result = $statement->execute();
				if (!$result){
					LogInfo ("SQL error: " . $statement->error);
				}
			}
			foreach ($model->getSportIds() as $id){
				$statement = $conn->prepare ("insert into mtm_location_sport (locationId, sportId) values (?, ?)");
				$statement->bind_param ("ii", $model->getId(), $id);
				$result = $statement->execute();
				if (!$result){
					LogInfo ("SQL error: " . $statement->error);
				}
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$foreignId = 0;
				$locationTypeIds = array();
				$mtmResult = $mtmConn->prepare ("select locationTypeId from mtm_location_locationType where locationId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($locationTypeIds, $foreignId);
				}
				$mtmResult->close();
				$foreignId = 0;
				$sportIds = array();
				$mtmResult = $mtmConn->prepare ("select sportId from mtm_location_sport where locationId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($sportIds, $foreignId);
				}
				$mtmResult->close();
				$mtmConn->close();

				return new Location ($id, $name, $street, $cityId, $phone, $locationTypeIds, $sportIds);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo location with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from location where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update location set name = ?, street = ?, cityId = ?, phone = ? where id = ?");
			$statement->bind_param ("ssisi", $model->getName(), $model->getStreet(), $model->getCityId(), $model->getPhone(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$foreignId = 0;
				$locationTypeIds = array();
				$mtmResult = $mtmConn->prepare ("select locationTypeId from mtm_location_locationType where locationId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($locationTypeIds, $foreignId);
				}
				$mtmResult->close();
				$foreignId = 0;
				$sportIds = array();
				$mtmResult = $mtmConn->prepare ("select sportId from mtm_location_sport where locationId = ?");
				$mtmResult->bind_param ("i", $id);
				$mtmResult->bind_result ($foreignId);
				$mtmResult->execute();
				while ($mtmResult->fetch()){
					array_push ($sportIds, $foreignId);
				}
				$mtmResult->close();
				$mtmConn->close();

				$model = new Location ($id, $name, $street, $cityId, $phone, $locationTypeIds, $sportIds);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestLocationRepository{
		public function getById ($id){
			return new Location($id, "test_name", "test_street", 0, "test_phone", array(0,1,2), array(0,1,2));
		}
	}

	class MySqlLocationTypeRepository{
		public function create ($model){
			LogInfo ("Creating locationType");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into locationType (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new LocationType ($id, $name);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo locationType with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from locationType where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update locationType set name = ? where id = ?");
			$statement->bind_param ("si", $model->getName(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating tag");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into tag (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new Tag ($id, $name);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo tag with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from tag where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update tag set name = ? where id = ?");
			$statement->bind_param ("si", $model->getName(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating user");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into user (type, method, passHash, nameFirst, nameLast, securityLevelId) values (?, ?, ?, ?, ?, ?)");
			$statement->bind_param ("iisssi", $model->getType(), $model->getMethod(), $model->getPassHash(), $model->getNameFirst(), $model->getNameLast(), $model->getSecurityLevelId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new User ($email, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo user with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from user where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update user set type = ?, method = ?, passHash = ?, nameFirst = ?, nameLast = ?, securityLevelId = ? where id = ?");
			$statement->bind_param ("iisssii", $model->getType(), $model->getMethod(), $model->getPassHash(), $model->getNameFirst(), $model->getNameLast(), $model->getSecurityLevelId(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating securityLevel");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into securityLevel (name) values (?)");
			$statement->bind_param ("s", $model->getName());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new SecurityLevel ($id, $name);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo securityLevel with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from securityLevel where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update securityLevel set name = ? where id = ?");
			$statement->bind_param ("si", $model->getName(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating city");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into city (name, state, country, longitude, latitude) values (?, ?, ?, ?, ?)");
			$statement->bind_param ("sssdd", $model->getName(), $model->getState(), $model->getCountry(), $model->getLongitude(), $model->getLatitude());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new City ($id, $name, $state, $country, $longitude, $latitude);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo city with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from city where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update city set name = ?, state = ?, country = ?, longitude = ?, latitude = ? where id = ?");
			$statement->bind_param ("sssddi", $model->getName(), $model->getState(), $model->getCountry(), $model->getLongitude(), $model->getLatitude(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating setting");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into setting (name, defaultValue) values (?, ?)");
			$statement->bind_param ("ss", $model->getName(), $model->getDefaultValue());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new Setting ($id, $name, $defaultValue);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo setting with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from setting where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update setting set name = ?, defaultValue = ? where id = ?");
			$statement->bind_param ("ssi", $model->getName(), $model->getDefaultValue(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
			LogInfo ("Creating userSetting");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into userSetting (userId, settingId, value) values (?, ?, ?)");
			$statement->bind_param ("iis", $model->getUserId(), $model->getSettingId(), $model->getValue());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			$model->setId ($conn->insert_id);
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new UserSetting ($userId, $settingId, $value);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo userSetting with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from userSetting where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update userSetting set userId = ?, settingId = ?, value = ? where id = ?");
			$statement->bind_param ("iisi", $model->getUserId(), $model->getSettingId(), $model->getValue(), $model->getId());
			$result = $statement->execute();
			if (!$result){
				LogInfo ("SQL error: " . $statement->error);
				return FALSE;
			}
			return TRUE;
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
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

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
