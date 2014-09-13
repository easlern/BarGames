<?php

	require_once ('code/startup.php');
	require_once ('userModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 500 Internal Server Error");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserRepository();
			if (IsAuthorized()){
				header ("HTTP/1.1 200 OK");
				$user = $repo->getUserById($args[0]);
				print ($user->toJson());
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
				header ("Location: /BarGames/api/user/1");
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
			LogInfo ("Deleting user with args " . print_r ($args, true));
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
