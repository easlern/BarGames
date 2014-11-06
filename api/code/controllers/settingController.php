<?php

	require_once ('code/startup.php');
	require_once ('settingModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class SettingController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getSettingRepository();
			if (IsAuthorized()){
				$setting = $repo->getById($args[0]);
				if ($setting != NULL){
					header ("HTTP/1.1 200 OK");
					print ($setting->toJson());
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
			$repo = Repositories::getSettingRepository();
			if (IsAuthorized()){
				$setting = $repo->getAll();
				if (count ($setting) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($setting as &$model){
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
			if (count ($args) < 2){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getSettingRepository();
				$model = new Setting(-1, $args[0], $args[1]);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/setting/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 2){
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
			LogInfo ("Deleting setting with args " . print_r ($args, true));
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
