<?php

	require_once ('code/startup.php');
	require_once ('gameModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$getVars = GetSanitizedGetVars();
	if (!isset ($getVars["id"])){
		$errorObject = new ApiErrorResponse ("Missing required parameters.");
		print (json_encode ($errorObject));
		exit();
	}

	$repo = Repositories::getGameRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$game = $repo->getGameById($postVars["id"]);
		print ($game->toJson());
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode($errorObject));
	}

	$putVars = GetSanitizedPutVars();
	if (!isset ($putVars["id"])){
		$errorObject = new ApiErrorResponse ("Missing required parameters.");
		print (json_encode ($errorObject));
		exit();
	}

	$repo = Repositories::getGameRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$game = $repo->getGameById($putVars["id"]);
		print ($game->toJson());
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode($errorObject));
	}

?>
