<?php

	require_once ('code/startup.php');
	require_once ('categoryModel.php');
	require_once ('restfulSetup.php');
	require_once ('repositories.php');

	class CategoryController{
		public function get ($args){
			if (count ($args) < 1){
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}

			$repo = Repositories::getCategoryRepository();
			if (IsAuthorized()){
				$category = $repo->getCategoryById($args[0]);
				print ($category->toJson());
			}
			else{
				$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");
				print (json_encode($errorObject));
			}
		}

		public function create ($args){
			if (count ($args) < 1){
				$errorObject = new ApiErrorResponse ("Missing required parameters.");
				print (json_encode ($errorObject));
				exit();
			}
			if (IsAdminAuthorized() && IsCsrfGood()){
				header ("HTTP/1.1 303 See Other");
				header ("Location: /BarGames/api/category/1");
			}
		}
	}

?>
