<?php
	require_once ('code/startup.php');
	require_once ("gameController.php");
	require_once ("locationController.php");
	require_once ("categoryController.php");
	require_once ("userController.php");

	if (strlen($_SERVER['REQUEST_URI']) > 1024) exit();

	$requestURI = explode('/', $_SERVER['REQUEST_URI']);
	$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
	for($i = 0; $i < sizeof ($scriptName); $i++)
	{
		if ($requestURI [$i] == $scriptName [$i])
		{
			unset($requestURI [$i]);
		}
	}
	$route = $requestURI;
	foreach ($route as $key=>$val){
		$queryLoc = strpos ($val, "?");
		if ($queryLoc !== FALSE) $route[$key] = substr ($val, 0, $queryLoc);
	}

	$command = SanitizeStringArray (array_values ($route));
	$controllerName = strtolower ($command [0]);
	$args = array_slice ($command, 1);
	$controllerInstance = NULL;

	switch ($controllerName){
		case "game":
			$controllerInstance = new GameController();
			break;
		case "location":
			$controllerInstance = new LocationController();
			break;
		case "category":
			$controllerInstance = new CategoryController();
			break;
		case "user":
			$controllerInstance = new UserController();
			break;
	}

	if ($controllerInstance !== NULL){
		switch ($_SERVER["REQUEST_METHOD"]){
			case "GET":
				$controllerInstance->get($args);
				break;
			case "POST":
				$args = GetSanitizedPostVars();
				$controllerInstance->create($args);
				break;
			case "PUT":
				$id = $args[0]; // Pull the index to be updated.
				$args = GetSanitizedPutVars();
				if (is_numeric ($id)) array_unshift ($args, $id);
				$controllerInstance->update($args);
				break;
			case "DELETE":
				$id = $args[0]; // Pull the index to be deleted.
				$args = GetSanitizedDeleteVars();
				if (is_numeric ($id)) array_unshift ($args, $id);
				$controllerInstance->delete($args);
				break;
			default:
				LogInfo ($_SERVER["REQUEST_METHOD"] . " method not allowed.");
				header ("HTTP/1.1 405 Method Not Allowed");
		}
	}
	else{
		header("HTTP/1.0 404 Not Found");
	}

?>
