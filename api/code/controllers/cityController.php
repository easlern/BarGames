<?php

	require_once ('code/startup.php');
	require_once ('cityModel.php');
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
				$city = $repo->getById($args[0]);
				if ($city != NULL){
					header ("HTTP/1.1 200 OK");
					print ($city->toJson());
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
				$city = $repo->getAll();
				if (count ($city) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($city as &$model){
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
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "name");
			array_push ($requiredArgs, "state");
			array_push ($requiredArgs, "longitude");
			array_push ($requiredArgs, "latitude");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, $args)){
					$argNamesSatisfied = FALSE;
				}
			}
			if (count ($args) < 5 || !$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getCityRepository();
				$name = in_array ("name", $args) ? $args["name"] : "";
				$state = in_array ("state", $args) ? $args["state"] : "";
				$country = in_array ("country", $args) ? $args["country"] : "";
				$longitude = in_array ("longitude", $args) ? $args["longitude"] : 0;
				$latitude = in_array ("latitude", $args) ? $args["latitude"] : 0;
				$model = new City(-1, $name, $state, $country, $longitude, $latitude);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/city/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 5){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 200 OK");
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function delete ($args){
			LogInfo ("Deleting city with args " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 204 No Content");
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
