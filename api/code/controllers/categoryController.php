<?php

	require_once ('code/startup.php');
	require_once ('categoryModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$getVars = GetSanitizedGetVars();
	if (!isset ($getVars["id"])){
		$errorObject = new ApiErrorResponse ("Missing required parameters.");
		print (json_encode ($errorObject));
		exit();
	}

	$repo = Repositories::getCategoryRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$category = $repo->getCategoryById($postVars["id"]);
		print ($category->toJson());
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

	$repo = Repositories::getCategoryRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$category = $repo->getCategoryById($putVars["id"]);
		print ($category->toJson());
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode($errorObject));
	}

?>
