<?php

	require_once ('code/startup.php');
	require_once ('userModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserRepository();
			if (IsAuthorized()){
				$user = $repo->getById($args[0]);
				if ($user != NULL){
					header ("HTTP/1.1 200 OK");
					print ($user->toJson());
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
			$repo = Repositories::getUserRepository();
			if (IsAuthorized()){
				$user = $repo->getAll();
				if (count ($user) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($user as &$model){
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
			array_push ($requiredArgs, "type");
			array_push ($requiredArgs, "method");
			array_push ($requiredArgs, "passHash");
			array_push ($requiredArgs, "nameFirst");
			array_push ($requiredArgs, "nameLast");
			array_push ($requiredArgs, "securityLevelId");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, $args)){
					$argNamesSatisfied = FALSE;
				}
			}
			if (count ($args) < 6 || !$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getUserRepository();
				$type = in_array ("type", $args) ? $args["type"] : 0;
				$method = in_array ("method", $args) ? $args["method"] : 0;
				$passHash = in_array ("passHash", $args) ? $args["passHash"] : "";
				$nameFirst = in_array ("nameFirst", $args) ? $args["nameFirst"] : "";
				$nameLast = in_array ("nameLast", $args) ? $args["nameLast"] : "";
				$securityLevelId = in_array ("securityLevelId", $args) ? $args["securityLevelId"] : 0;
				$model = new User(-1, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/user/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 6){
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
			LogInfo ("Deleting user with args " . print_r ($args, true));
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
