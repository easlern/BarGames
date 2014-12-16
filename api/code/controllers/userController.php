<?php

	require_once ('code/startup.php');
	require_once ('UserModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserRepository();
			if (IsAuthorized()){
				$User = $repo->getById ($args[0]);
				if ($User != NULL){
					header ("HTTP/1.1 200 OK");
					print ($User->toJson());
				}
				else{
					header ("HTTP/1.1 404 Not found");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function getAll(){
			$repo = Repositories::getUserRepository();
			if (IsAuthorized()){
				$User = $repo->getAll();
				if (count ($User) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($User as &$model){
						array_push ($models, $model->toStdClass());
					}
					print json_encode ($models);
				}
				else{
					header ("HTTP/1.1 404 Not found");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function create ($args){
			LogInfo ("Creating User with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "FirstName");
			array_push ($requiredArgs, "LastName");
			array_push ($requiredArgs, "Email");
			array_push ($requiredArgs, "CityID");
			array_push ($requiredArgs, "CREATEDATE");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, array_keys ($args))){
					$argNamesSatisfied = FALSE;
				}
			}
			if (!$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getUserRepository();
				$AccountID = in_array ("AccountID", array_keys ($args)) ? $args["AccountID"] : 0;
				$FirstName = in_array ("FirstName", array_keys ($args)) ? $args["FirstName"] : "";
				$LastName = in_array ("LastName", array_keys ($args)) ? $args["LastName"] : "";
				$Email = in_array ("Email", array_keys ($args)) ? $args["Email"] : "";
				$CityID = in_array ("CityID", array_keys ($args)) ? $args["CityID"] : 0;
				$MiddleName = in_array ("MiddleName", array_keys ($args)) ? $args["MiddleName"] : "";
				$DisplayName = in_array ("DisplayName", array_keys ($args)) ? $args["DisplayName"] : "";
				$Description = in_array ("Description", array_keys ($args)) ? $args["Description"] : "";
				$BirthDate = in_array ("BirthDate", array_keys ($args)) ? $args["BirthDate"] : 0;
				$CREATEDATE = in_array ("CREATEDATE", array_keys ($args)) ? $args["CREATEDATE"] : 0;
				$LASTMODIFIEDDATE = in_array ("LASTMODIFIEDDATE", array_keys ($args)) ? $args["LASTMODIFIEDDATE"] : 0;
				$model = new User(-1, $AccountID, $FirstName, $LastName, $Email, $CityID, $MiddleName, $DisplayName, $Description, $BirthDate, $CREATEDATE, $LASTMODIFIEDDATE);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/User/" . $model->getId());
				}
				else{
					header ("HTTP/1.1 500 Internal Server Error");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			LogInfo ("Updating User with args: " . print_r ($args, true));
			$repo = Repositories::getUserRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "AccountID") $existing->setAccountID ($value);
					if ($key === "FirstName") $existing->setFirstName ($value);
					if ($key === "LastName") $existing->setLastName ($value);
					if ($key === "Email") $existing->setEmail ($value);
					if ($key === "CityID") $existing->setCityID ($value);
					if ($key === "MiddleName") $existing->setMiddleName ($value);
					if ($key === "DisplayName") $existing->setDisplayName ($value);
					if ($key === "Description") $existing->setDescription ($value);
					if ($key === "BirthDate") $existing->setBirthDate ($value);
					if ($key === "CREATEDATE") $existing->setCREATEDATE ($value);
					if ($key === "LASTMODIFIEDDATE") $existing->setLASTMODIFIEDDATE ($value);
				}
				if ($repo->update ($existing)){
					header ("HTTP/1.1 200 OK");
				}
				else{
					header ("HTTP/1.1 500 Internal Server Error");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function delete ($args){
			LogInfo ("Deleting User with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getUserRepository();
				$repo->delete ($args[0]);
				header ("HTTP/1.1 204 No Content");
			}
			else{
				LogInfo ("Delete is not authorized.");
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
