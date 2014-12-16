<?php

	require_once ("startup.php");
	require_once ("initializeDb.php");


	class MySqlAccountRepository{
		public function create ($model){
			LogInfo ("Creating Account");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into Account (Password, Username, AccountPicturePath, AccountTypeID, IsDisabled, LastLoginDate, CREATEDATE, LASTMODIFIEDDATE) values (?, ?, ?, ?, ?, ?, ?, ?)");
			$statement->bind_param ("sssi", $model->getPassword(), $model->getUsername(), $model->getAccountPicturePath(), $model->getAccountTypeID(), $model->getIsDisabled(), $model->getLastLoginDate(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE());
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
			$result = $conn->prepare ("select id, Password, Username, AccountPicturePath, AccountTypeID, IsDisabled, LastLoginDate, CREATEDATE, LASTMODIFIEDDATE from Account where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$ID = 0;
			$Password = "";
			$Username = "";
			$AccountPicturePath = "";
			$AccountTypeID = 0;
			$IsDisabled = 0;
			$LastLoginDate = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$result->bind_result ($ID, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE);
			if ($result->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new Account ($ID, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo Account with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from Account where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update Account set Password = ?, Username = ?, AccountPicturePath = ?, AccountTypeID = ?, IsDisabled = ?, LastLoginDate = ?, CREATEDATE = ?, LASTMODIFIEDDATE = ? where id = ?");
			$statement->bind_param ("sssii", $model->getPassword(), $model->getUsername(), $model->getAccountPicturePath(), $model->getAccountTypeID(), $model->getIsDisabled(), $model->getLastLoginDate(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE(), $model->getId());
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
			$statement = $conn->prepare ("select id, Password, Username, AccountPicturePath, AccountTypeID, IsDisabled, LastLoginDate, CREATEDATE, LASTMODIFIEDDATE from Account");
			$statement->execute();

			$ID = 0;
			$Password = "";
			$Username = "";
			$AccountPicturePath = "";
			$AccountTypeID = 0;
			$IsDisabled = 0;
			$LastLoginDate = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$statement->bind_result ($ID, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE);
			$statement->store_result();
			while ($statement->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				$model = new Account ($ID, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestAccountRepository{
		public function getById ($id){
			return new Account($id, "test_Password", "test_Username", "test_AccountPicturePath", 0, , , , );
		}
	}

	class MySqlUserRepository{
		public function create ($model){
			LogInfo ("Creating User");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into User (AccountID, FirstName, LastName, Email, CityID, MiddleName, DisplayName, Description, BirthDate, CREATEDATE, LASTMODIFIEDDATE) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$statement->bind_param ("isssisss", $model->getAccountID(), $model->getFirstName(), $model->getLastName(), $model->getEmail(), $model->getCityID(), $model->getMiddleName(), $model->getDisplayName(), $model->getDescription(), $model->getBirthDate(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE());
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
			$result = $conn->prepare ("select id, AccountID, FirstName, LastName, Email, CityID, MiddleName, DisplayName, Description, BirthDate, CREATEDATE, LASTMODIFIEDDATE from User where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$ID = 0;
			$AccountID = 0;
			$FirstName = "";
			$LastName = "";
			$Email = "";
			$CityID = 0;
			$MiddleName = "";
			$DisplayName = "";
			$Description = "";
			$BirthDate = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$result->bind_result ($ID, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE);
			if ($result->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new User ($ID, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo User with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from User where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update User set AccountID = ?, FirstName = ?, LastName = ?, Email = ?, CityID = ?, MiddleName = ?, DisplayName = ?, Description = ?, BirthDate = ?, CREATEDATE = ?, LASTMODIFIEDDATE = ? where id = ?");
			$statement->bind_param ("isssisssi", $model->getAccountID(), $model->getFirstName(), $model->getLastName(), $model->getEmail(), $model->getCityID(), $model->getMiddleName(), $model->getDisplayName(), $model->getDescription(), $model->getBirthDate(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE(), $model->getId());
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
			$statement = $conn->prepare ("select id, AccountID, FirstName, LastName, Email, CityID, MiddleName, DisplayName, Description, BirthDate, CREATEDATE, LASTMODIFIEDDATE from User");
			$statement->execute();

			$ID = 0;
			$AccountID = 0;
			$FirstName = "";
			$LastName = "";
			$Email = "";
			$CityID = 0;
			$MiddleName = "";
			$DisplayName = "";
			$Description = "";
			$BirthDate = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$statement->bind_result ($ID, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE);
			$statement->store_result();
			while ($statement->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				$model = new User ($ID, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestUserRepository{
		public function getById ($id){
			return new User($id, 0, "test_FirstName", "test_LastName", "test_Email", 0, "test_MiddleName", "test_DisplayName", "test_Description", , , );
		}
	}

	class MySqlUserProfileRepository{
		public function create ($model){
			LogInfo ("Creating UserProfile");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into UserProfile (UserID, AboutMe, Interests, CREATEDATE, LASTMODIFIEDDATE) values (?, ?, ?, ?, ?)");
			$statement->bind_param ("iss", $model->getUserID(), $model->getAboutMe(), $model->getInterests(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE());
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
			$result = $conn->prepare ("select id, UserID, AboutMe, Interests, CREATEDATE, LASTMODIFIEDDATE from UserProfile where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$ID = 0;
			$UserID = 0;
			$AboutMe = "";
			$Interests = "";
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$result->bind_result ($ID, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE);
			if ($result->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new UserProfile ($ID, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo UserProfile with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from UserProfile where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update UserProfile set UserID = ?, AboutMe = ?, Interests = ?, CREATEDATE = ?, LASTMODIFIEDDATE = ? where id = ?");
			$statement->bind_param ("issi", $model->getUserID(), $model->getAboutMe(), $model->getInterests(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE(), $model->getId());
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
			$statement = $conn->prepare ("select id, UserID, AboutMe, Interests, CREATEDATE, LASTMODIFIEDDATE from UserProfile");
			$statement->execute();

			$ID = 0;
			$UserID = 0;
			$AboutMe = "";
			$Interests = "";
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$statement->bind_result ($ID, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE);
			$statement->store_result();
			while ($statement->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				$model = new UserProfile ($ID, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestUserProfileRepository{
		public function getById ($id){
			return new UserProfile($id, 0, "test_AboutMe", "test_Interests", , );
		}
	}

	class MySqlCityRepository{
		public function create ($model){
			LogInfo ("Creating City");

			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("insert into City (Name, State, Country, Longitude, Latitude, CREATEDATE, LASTMODIFIEDDATE) values (?, ?, ?, ?, ?, ?, ?)");
			$statement->bind_param ("sssdd", $model->getName(), $model->getState(), $model->getCountry(), $model->getLongitude(), $model->getLatitude(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE());
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
			$result = $conn->prepare ("select id, Name, State, Country, Longitude, Latitude, CREATEDATE, LASTMODIFIEDDATE from City where id = ?");
			$result->bind_param ("i", $id);
			$result->execute();

			$ID = 0;
			$Name = "";
			$State = "";
			$Country = "";
			$Longitude = 0;
			$Latitude = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$result->bind_result ($ID, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE);
			if ($result->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				return new City ($ID, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE);
			}
			return NULL;
		}

		public function delete ($id){
			LogInfo ("Deleting in repo City with id $id.");
			$conn = connectAsWebUser();
			if (!$conn) return NULL;
			$statement = $conn->prepare ("delete from City where id = ?");
			$statement->bind_param ("i", $id);
			$statement->execute();
		}
		public function update ($model){
			$conn = connectAsWebUser();
			if (!$conn) return FALSE;
			$statement = $conn->prepare ("update City set Name = ?, State = ?, Country = ?, Longitude = ?, Latitude = ?, CREATEDATE = ?, LASTMODIFIEDDATE = ? where id = ?");
			$statement->bind_param ("sssddi", $model->getName(), $model->getState(), $model->getCountry(), $model->getLongitude(), $model->getLatitude(), $model->getCREATEDATE(), $model->getLASTMODIFIEDDATE(), $model->getId());
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
			$statement = $conn->prepare ("select id, Name, State, Country, Longitude, Latitude, CREATEDATE, LASTMODIFIEDDATE from City");
			$statement->execute();

			$ID = 0;
			$Name = "";
			$State = "";
			$Country = "";
			$Longitude = 0;
			$Latitude = 0;
			$CREATEDATE = 0;
			$LASTMODIFIEDDATE = 0;
			$statement->bind_result ($ID, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE);
			$statement->store_result();
			while ($statement->fetch()){
				$mtmConn = connectAsWebUser();
				$mtmConn->close();

				$model = new City ($ID, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE);
				array_push ($results, $model);
			}
			return $results;
		}

	}

	class TestCityRepository{
		public function getById ($id){
			return new City($id, "test_Name", "test_State", "test_Country", 0, 0, , );
		}
	}

	class Repositories{
		public static function getAccountRepository(){
			return new MySqlAccountRepository();
		}
		public static function getUserRepository(){
			return new MySqlUserRepository();
		}
		public static function getUserProfileRepository(){
			return new MySqlUserProfileRepository();
		}
		public static function getCityRepository(){
			return new MySqlCityRepository();
		}
	}

?>
