<?php
	require_once ('locationModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$repo = Repositories::getLocationRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$location = $repo->getById(GetSanitizedPostVars()["id"]);
		print (json_encode ($location));
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode ($errorObject));
	}
?>