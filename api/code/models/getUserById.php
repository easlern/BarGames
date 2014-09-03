<?php

	require_once ('userModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$repo = Repositories::getUserRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$user = $repo->getById(GetSanitizedPostVars()["id"]);
		print (json_encode ($user));
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode ($errorObject));
	}

?>
