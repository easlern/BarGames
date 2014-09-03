<?php

	require_once ('code/startup.php');
	require_once ('categoryModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$postVars = GetSanitizedPostVars();
	if (!isset ($postVars["id"])){
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

?>
