<?php

	require_once ('code/startup.php');
	require_once ('userModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$putVars = GetSanitizedPutVars();
	if (!isset ($putVars["login"])){
		$errorObject = new ApiErrorResponse ("Missing required parameters.");
		print (json_encode ($errorObject));
		exit();
	}

	$repo = Repositories::getUserRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$user = $repo->getUserById($putVars["id"]);
		print ($user->toJson());
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode($errorObject));
	}

?>
