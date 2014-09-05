<?php

	require_once ('code/startup.php');
	require_once ('locationModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class LocationController{
		public function get ($args){
			if (array_count_values ($args) < 1){
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getLocationRepository();
			if (IsAuthorized() && IsCsrfGood()){
				$location = $repo->getLocationById($args[0]);
				print ($location->toJson());
			}
			else{
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
