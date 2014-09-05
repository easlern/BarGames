<?php
	require_once('code/startup.php');

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

	$command = SanitizeStringArray (array_values ($requestURI));
	$controller = strtolower ($command [0]);
	$args = array_slice ($command, 1);

	switch ($controller){
		case "game":
			$cont = new game();
			$cont->handle ($args);
			break;
		case "location":
			$cont = new location();
			$cont->handle ($args);
			break;
		case "category":
			$cont = new category();
			$cont->handle ($args);
			break;
		case "user":
			$cont = new user();
			$cont->handle ($args);
			break;
	}
?>
