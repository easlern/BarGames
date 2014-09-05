<?php

	require_once ('code/startup.php');
	require_once ('gameModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class GameController{
		public function get ($args){
			if (array_count_values ($args) < 1){
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getGameRepository();
			if (IsAuthorized() && IsCsrfGood()){
				$game = $repo->getGameById($args[0]);
				print ($game->toJson());
			}
			else{
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
