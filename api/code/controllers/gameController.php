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
				$game = $repo->getById($args[0]);
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
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "locationId");
			array_push ($requiredArgs, "name");
			array_push ($requiredArgs, "sportId");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, $args)){
					$argNamesSatisfied = FALSE;
				}
			}
			if (count ($args) < 5 || !$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getGameRepository();
				$locationId = in_array ("locationId", $args) ? $args["locationId"] : 0;
				$name = in_array ("name", $args) ? $args["name"] : "";
				$tagIds = in_array ("tagIds", $args) ? $args["tagIds"] : 0;
				$sportId = in_array ("sportId", $args) ? $args["sportId"] : 0;
				$teamIds = in_array ("teamIds", $args) ? $args["teamIds"] : 0;
				$model = new Game(-1, $locationId, $name, $sportId);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/game/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 5){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 200 OK");
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function delete ($args){
			LogInfo ("Deleting game with args " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 204 No Content");
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}
	}

?>
