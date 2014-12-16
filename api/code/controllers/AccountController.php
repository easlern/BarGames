<?php

	require_once ('code/startup.php');
	require_once ('AccountModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class AccountController{
		public function get ($args){
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getAccountRepository();
			if (IsAuthorized()){
				$Account = $repo->getById ($args[0]);
				if ($Account != NULL){
					header ("HTTP/1.1 200 OK");
					print ($Account->toJson());
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
			$repo = Repositories::getAccountRepository();
			if (IsAuthorized()){
				$Account = $repo->getAll();
				if (count ($Account) > 0){
					header ("HTTP/1.1 200 OK");
					$models = array();
					foreach ($Account as &$model){
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
			LogInfo ("Creating Account with args: " . print_r ($args, true));
			$argNamesSatisfied = TRUE;
			$requiredArgs = array();
			array_push ($requiredArgs, "Password");
			array_push ($requiredArgs, "Username");
			array_push ($requiredArgs, "AccountPicturePath");
			array_push ($requiredArgs, "AccountTypeID");
			array_push ($requiredArgs, "IsDisabled");
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
				$repo = Repositories::getAccountRepository();
				$Password = in_array ("Password", array_keys ($args)) ? $args["Password"] : "";
				$Username = in_array ("Username", array_keys ($args)) ? $args["Username"] : "";
				$AccountPicturePath = in_array ("AccountPicturePath", array_keys ($args)) ? $args["AccountPicturePath"] : "";
				$AccountTypeID = in_array ("AccountTypeID", array_keys ($args)) ? $args["AccountTypeID"] : 0;
				$IsDisabled = in_array ("IsDisabled", array_keys ($args)) ? $args["IsDisabled"] : 0;
				$LastLoginDate = in_array ("LastLoginDate", array_keys ($args)) ? $args["LastLoginDate"] : 0;
				$CREATEDATE = in_array ("CREATEDATE", array_keys ($args)) ? $args["CREATEDATE"] : 0;
				$LASTMODIFIEDDATE = in_array ("LASTMODIFIEDDATE", array_keys ($args)) ? $args["LASTMODIFIEDDATE"] : 0;
				$model = new Account(-1, $Password, $Username, $AccountPicturePath, $AccountTypeID, $IsDisabled, $LastLoginDate, $CREATEDATE, $LASTMODIFIEDDATE);
				if ($repo->create($model)){
					header ("HTTP/1.1 303 See Other");
					header ("Location: /api/Account/" . $model->getId());
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
			LogInfo ("Updating Account with args: " . print_r ($args, true));
			$repo = Repositories::getAccountRepository();
			$existing = $repo->getById ($args[0]);
			if ($existing == NULL){
				header ("HTTP/1.1 404 Not Found");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				foreach ($args as $key => $value){
					if ($key === "Password") $existing->setPassword ($value);
					if ($key === "Username") $existing->setUsername ($value);
					if ($key === "AccountPicturePath") $existing->setAccountPicturePath ($value);
					if ($key === "AccountTypeID") $existing->setAccountTypeID ($value);
					if ($key === "IsDisabled") $existing->setIsDisabled ($value);
					if ($key === "LastLoginDate") $existing->setLastLoginDate ($value);
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
			LogInfo ("Deleting Account with args: " . print_r ($args, true));
			if (count ($args) < 1){
				header ("HTTP/1.1 400 Bad Request");
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				LogInfo ("Delete is authorized.");
				$repo = Repositories::getAccountRepository();
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
