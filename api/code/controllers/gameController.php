<?php

	require_once ('code/startup.php');
	require_once ('gameModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class GameController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getGameRepository();
			if (IsAuthorized()){
				$game = $repo->getById ($args[0]);
				if ($game != NULL){
					header ("HTTP/1.1 200 OK");
					print ($game->toJson());
				}
				else{
					header ("HTTP/1.1 404 Not found");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function getAll(){
			$repo = Repositories::getGameRepository();
			if (IsAuthorized()){
				$game = $repo->getAll();
				if (count ($game) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($game as &$model){
						array_push ($models, $model->toStdClass());
					}
					print json_encode ($models);
				}
				else{
					header ("HTTP/1.1 404 Not found");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated.");
				print (json_encode($errorObject));
			}
		}

		public function create ($args){
			LogInfo ("Creating game with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "locationId");
			array_push ($requiredArgs, "name");
			array_push ($requiredArgs, "sportId");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, array_keys ($args))){
					$argNamesSatisfied = FALSE;
				}
			}
			if (!$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getGameRepository();
				$locationId = in_array ("locationId", array_keys ($args)) ? $args["locationId"] : 0;
				$name = in_array ("name", array_keys ($args)) ? $args["name"] : "";
				$tagIds = array();
				if (in_array ("tagIds", array_keys ($args))){
					$decodedArray = json_decode ($args ["tagIds"], TRUE, 1);
					foreach ($decodedArray as $key => $value){
						array_push ($decodedArray, $value);
					}
				}
				$sportId = in_array ("sportId", array_keys ($args)) ? $args["sportId"] : 0;
				$teamIds = array();
				if (in_array ("teamIds", array_keys ($args))){
					$decodedArray = json_decode ($args ["teamIds"], TRUE, 1);
					foreach ($decodedArray as $key => $value){
						array_push ($decodedArray, $value);
					}
				}
				$model = new Game(-1, $locationId, $name, $tagIds, $sportId, $teamIds);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/game/" . $model->getId());
				}
				else{
					header ("HTTP/1.1 500 Internal Server Error");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			LogInfo ("Updating game with args: " . print_r ($args, true));
			$repo = Repositories::getGameRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "locationId") $existing->setLocationId ($value);
					if ($key === "name") $existing->setName ($value);
					if ($key === "sportId") $existing->setSportId ($value);
				}
				if ($repo->update ($existing)){
					header ("HTTP/1.1 200 OK");
				}
				else{
					header ("HTTP/1.1 500 Internal Server Error");
				}
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function delete ($args){
			LogInfo ("Deleting game with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getGameRepository();
				$repo->delete ($args[0]);
				header ("HTTP/1.1 204 No Content");
			}
			else{
				LogInfo ("Delete is not authorized.");
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
