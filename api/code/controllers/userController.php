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
				$user = $repo->getById ($args[0]);
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
			LogInfo ("Creating user with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "type");
			array_push ($requiredArgs, "method");
			array_push ($requiredArgs, "passHash");
			array_push ($requiredArgs, "nameFirst");
			array_push ($requiredArgs, "nameLast");
			array_push ($requiredArgs, "securityLevelId");
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
				$repo = Repositories::getUserRepository();
				$type = in_array ("type", array_keys ($args)) ? $args["type"] : 0;
				$method = in_array ("method", array_keys ($args)) ? $args["method"] : 0;
				$passHash = in_array ("passHash", array_keys ($args)) ? $args["passHash"] : "";
				$nameFirst = in_array ("nameFirst", array_keys ($args)) ? $args["nameFirst"] : "";
				$nameLast = in_array ("nameLast", array_keys ($args)) ? $args["nameLast"] : "";
				$securityLevelId = in_array ("securityLevelId", array_keys ($args)) ? $args["securityLevelId"] : 0;
				$model = new User(-1, $type, $method, $passHash, $nameFirst, $nameLast, $securityLevelId);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/user/" . $model->getId());
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
			LogInfo ("Updating user with args: " . print_r ($args, true));
			$repo = Repositories::getUserRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "type") $existing->setType ($value);
					if ($key === "method") $existing->setMethod ($value);
					if ($key === "passHash") $existing->setPassHash ($value);
					if ($key === "nameFirst") $existing->setNameFirst ($value);
					if ($key === "nameLast") $existing->setNameLast ($value);
					if ($key === "securityLevelId") $existing->setSecurityLevelId ($value);
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
			LogInfo ("Deleting user with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getUserRepository();
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
