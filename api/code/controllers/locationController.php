<?php

	require_once ('code/startup.php');
	require_once ('locationModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class LocationController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getLocationRepository();
			if (IsAuthorized()){
				header ("HTTP/1.1 200 OK");
				$location = $repo->getLocationById($args[0]);
				print ($location->toJson());
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function create ($args){
			if (count ($args) < 5){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 303 See Other");
				header ("Location: /BarGames/api/location/1");
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 5){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 200 OK");
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function delete ($args){
			LogInfo ("Deleting location with args " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 204 No Content");
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
