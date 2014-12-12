<?php

	require_once ('code/startup.php');
	require_once ('locationModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class LocationController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getLocationRepository();
			if (IsAuthorized()){
				$location = $repo->getById ($args[0]);
				if ($location != NULL){
					header ("HTTP/1.1 200 OK");
					print ($location->toJson());
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
			$repo = Repositories::getLocationRepository();
			if (IsAuthorized()){
				$location = $repo->getAll();
				if (count ($location) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($location as &$model){
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
			LogInfo ("Creating location with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "name");
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
				$repo = Repositories::getLocationRepository();
				$name = in_array ("name", array_keys ($args)) ? $args["name"] : "";
				$street = in_array ("street", array_keys ($args)) ? $args["street"] : "";
				$cityId = in_array ("cityId", array_keys ($args)) ? $args["cityId"] : 0;
				$phone = in_array ("phone", array_keys ($args)) ? $args["phone"] : "";
				$locationTypeIds = array();
				if (in_array ("locationTypeIds", array_keys ($args))){
					$decodedArray = json_decode ($args ["locationTypeIds"], TRUE, 2);
					foreach ($decodedArray as $key => $value){
						array_push ($locationTypeIds, $value);
					}
				}
				$sportIds = array();
				if (in_array ("sportIds", array_keys ($args))){
					$decodedArray = json_decode ($args ["sportIds"], TRUE, 2);
					foreach ($decodedArray as $key => $value){
						array_push ($sportIds, $value);
					}
				}
				$model = new Location(-1, $name, $street, $cityId, $phone, $locationTypeIds, $sportIds);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/location/" . $model->getId());
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
			LogInfo ("Updating location with args: " . print_r ($args, true));
			$repo = Repositories::getLocationRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "name") $existing->setName ($value);
					if ($key === "street") $existing->setStreet ($value);
					if ($key === "cityId") $existing->setCityId ($value);
					if ($key === "phone") $existing->setPhone ($value);
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
			LogInfo ("Deleting location with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getLocationRepository();
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
