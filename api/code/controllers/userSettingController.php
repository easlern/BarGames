<?php

	require_once ('code/startup.php');
	require_once ('userSettingModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserSettingController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserSettingRepository();
			if (IsAuthorized()){
				$userSetting = $repo->getById($args[0]);
				if ($userSetting != NULL){
					header ("HTTP/1.1 200 OK");
					print ($userSetting->toJson());
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
			$repo = Repositories::getUserSettingRepository();
			if (IsAuthorized()){
				$userSetting = $repo->getAll();
				if (count ($userSetting) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($userSetting as &$model){
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
			array_push ($requiredArgs, "userId");
			array_push ($requiredArgs, "settingId");
			array_push ($requiredArgs, "value");
			foreach ($requiredArgs as $requiredArg){
				if (!in_array ($requiredArg, $args)){
					$argNamesSatisfied = FALSE;
				}
			}
			if (count ($args) < 3 || !$argNamesSatisfied){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				$repo = Repositories::getUserSettingRepository();
				$userId = in_array ("userId", $args) ? $args["userId"] : 0;
				$settingId = in_array ("settingId", $args) ? $args["settingId"] : 0;
				$value = in_array ("value", $args) ? $args["value"] : "";
				$model = new UserSetting(-1, $userId, $settingId, $value);
				$repo->create($model);
				header ("HTTP/1.1 303 See Other");
				header ("Location: /api/userSetting/" . $model->getId());
			}
			else{
				header ("HTTP/1.1 403 Forbidden");
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function update ($args){
			if (count ($args) < 3){
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
			LogInfo ("Deleting userSetting with args " . print_r ($args, true));
			if (count ($args) < 0){
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
