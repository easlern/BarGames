<?php

	require_once ('categoryModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$repo = Repositories::getCategoryRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$category = $repo->getById(GetSanitizedPostVars()["id"]);
		print (json_encode ($category));
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode ($errorObject));
	}

?>
