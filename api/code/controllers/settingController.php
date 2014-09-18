<?php

	require_once ('code/startup.php');
	require_once ('settingModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class SettingController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getSettingRepository();
			if (IsAuthorized()){
				header ("HTTP/1.1 200 OK");
				$setting = $repo->getSettingById($args[0]);
				print ($setting->toJson());
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function create ($args){
			if (count ($args) < 2){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 303 See Other");
				header ("Location: /BarGames/api/setting/1");
			}
			else{
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 2){
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
			LogInfo ("Deleting setting with args " . print_r ($args, true));
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
