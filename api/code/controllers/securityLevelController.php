<?php

	require_once ('code/startup.php');
	require_once ('securityLevelModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class SecurityLevelController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getSecurityLevelRepository();
			if (IsAuthorized()){
				$securityLevel = $repo->getById($args[0]);
				if ($securityLevel != NULL){
					header ("HTTP/1.1 200 OK");
					print ($securityLevel->toJson());
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
			$repo = Repositories::getSecurityLevelRepository();
			if (IsAuthorized()){
				$securityLevel = $repo->getAll();
				if (count ($securityLevel) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($securityLevel as &$model){
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
			array_push ($requiredArgs, "name");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, $args)){
					$argNamesSatisfied = FALSE;
				}
			}
			if (count ($args) < 1 || !$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getSecurityLevelRepository();
				$name = in_array ("name", $args) ? $args["name"] : "";
				$model = new SecurityLevel(-1, $name);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/securityLevel/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 1){
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
			LogInfo ("Deleting securityLevel with args " . print_r ($args, true));
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
