<?php
	require_once ('code/startup.php');
	require_once ("AccountController.php");
	require_once ("UserController.php");
	require_once ("UserProfileController.php");
	require_once ("CityController.php");

	if (strlen($_SERVER['REQUEST_URI']) > 1024) exit();

	$requestURI = explode('/', $_SERVER['REQUEST_URI']);
	$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
	for($i = 0; $i < sizeof ($scriptName); $i++)
	{
		if ($requestURI [$i] === $scriptName [$i])
		{
			unset($requestURI [$i]);
		}
	}
	$route = $requestURI;
	foreach ($route as $key=>$val){
		$queryLoc = strpos ($val, "?");
		if ($queryLoc !== FALSE) $route[$key] = substr ($val, 0, $queryLoc);
	}

	$command = SanitizeStringArray ($route);
	$controllerName = array_values ($command);
	$controllerName = strtolower ($controllerName [0]);
	$args = array_filter (array_slice ($command, 1));
	$controllerInstance = NULL;

	switch ($controllerName){
		case "account":
			$controllerInstance = new AccountController();
			break;
		case "user":
			$controllerInstance = new UserController();
			break;
		case "userprofile":
			$controllerInstance = new UserProfileController();
			break;
		case "city":
			$controllerInstance = new CityController();
			break;
	}

	if ($controllerInstance !== NULL){
		switch ($_SERVER["REQUEST_METHOD"]){
			case "GET":
				if (count ($args) == 0) $controllerInstance->getAll();
				else $controllerInstance->get($args);
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
		header("HTTP/1.1 404 Not Found");
	}

?>
