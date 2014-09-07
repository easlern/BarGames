<?php

	require_once ('code/startup.php');
	require_once ('userModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserController{
		public function get ($args){
			if (array_count_values ($args) < 1){
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserRepository();
			if (IsAuthorized() && IsCsrfGood()){
				$user = $repo->getUserById($args[0]);
				print ($user->toJson());
			}
			else{
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
