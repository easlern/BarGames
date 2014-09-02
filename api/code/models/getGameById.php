<?php
	require_once ('gameModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	$repo = Repositories::getGameRepository();
	if (IsAuthorized() && IsCsrfGood()){
		$game = $repo->getById(GetSanitizedPostVars()["id"]);
		print (json_encode ($game));
	}
	else{
		$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
		print (json_encode ($errorObject));
	}
?>