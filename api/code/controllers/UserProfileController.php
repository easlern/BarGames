<?php

	require_once ('code/startup.php');
	require_once ('UserProfileModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class UserProfileController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getUserProfileRepository();
			if (IsAuthorized()){
				$UserProfile = $repo->getById ($args[0]);
				if ($UserProfile != NULL){
					header ("HTTP/1.1 200 OK");
					print ($UserProfile->toJson());
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
			$repo = Repositories::getUserProfileRepository();
			if (IsAuthorized()){
				$UserProfile = $repo->getAll();
				if (count ($UserProfile) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($UserProfile as &$model){
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
			LogInfo ("Creating UserProfile with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "UserID");
			array_push ($requiredArgs, "CREATEDATE");
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
				$repo = Repositories::getUserProfileRepository();
				$UserID = in_array ("UserID", array_keys ($args)) ? $args["UserID"] : 0;
				$AboutMe = in_array ("AboutMe", array_keys ($args)) ? $args["AboutMe"] : "";
				$Interests = in_array ("Interests", array_keys ($args)) ? $args["Interests"] : "";
				$CREATEDATE = in_array ("CREATEDATE", array_keys ($args)) ? $args["CREATEDATE"] : 0;
				$LASTMODIFIEDDATE = in_array ("LASTMODIFIEDDATE", array_keys ($args)) ? $args["LASTMODIFIEDDATE"] : 0;
				$model = new UserProfile(-1, $UserID, $AboutMe, $Interests, $CREATEDATE, $LASTMODIFIEDDATE);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/UserProfile/" . $model->getId());
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
			LogInfo ("Updating UserProfile with args: " . print_r ($args, true));
			$repo = Repositories::getUserProfileRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "UserID") $existing->setUserID ($value);
					if ($key === "AboutMe") $existing->setAboutMe ($value);
					if ($key === "Interests") $existing->setInterests ($value);
					if ($key === "CREATEDATE") $existing->setCREATEDATE ($value);
					if ($key === "LASTMODIFIEDDATE") $existing->setLASTMODIFIEDDATE ($value);
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
			LogInfo ("Deleting UserProfile with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getUserProfileRepository();
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
