<?php

	require_once ('code/startup.php');
	require_once ('CityModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class CityController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getCityRepository();
			if (IsAuthorized()){
				$City = $repo->getById ($args[0]);
				if ($City != NULL){
					header ("HTTP/1.1 200 OK");
					print ($City->toJson());
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
			$repo = Repositories::getCityRepository();
			if (IsAuthorized()){
				$City = $repo->getAll();
				if (count ($City) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($City as &$model){
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
			LogInfo ("Creating City with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "Name");
			array_push ($requiredArgs, "State");
			array_push ($requiredArgs, "Country");
			array_push ($requiredArgs, "Longitude");
			array_push ($requiredArgs, "Latitude");
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
				$repo = Repositories::getCityRepository();
				$Name = in_array ("Name", array_keys ($args)) ? $args["Name"] : "";
				$State = in_array ("State", array_keys ($args)) ? $args["State"] : "";
				$Country = in_array ("Country", array_keys ($args)) ? $args["Country"] : "";
				$Longitude = in_array ("Longitude", array_keys ($args)) ? $args["Longitude"] : 0;
				$Latitude = in_array ("Latitude", array_keys ($args)) ? $args["Latitude"] : 0;
				$CREATEDATE = in_array ("CREATEDATE", array_keys ($args)) ? $args["CREATEDATE"] : 0;
				$LASTMODIFIEDDATE = in_array ("LASTMODIFIEDDATE", array_keys ($args)) ? $args["LASTMODIFIEDDATE"] : 0;
				$model = new City(-1, $Name, $State, $Country, $Longitude, $Latitude, $CREATEDATE, $LASTMODIFIEDDATE);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/City/" . $model->getId());
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
			LogInfo ("Updating City with args: " . print_r ($args, true));
			$repo = Repositories::getCityRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "Name") $existing->setName ($value);
					if ($key === "State") $existing->setState ($value);
					if ($key === "Country") $existing->setCountry ($value);
					if ($key === "Longitude") $existing->setLongitude ($value);
					if ($key === "Latitude") $existing->setLatitude ($value);
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
			LogInfo ("Deleting City with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getCityRepository();
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
