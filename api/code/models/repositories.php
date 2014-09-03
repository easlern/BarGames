<?php

	class TestGameRepository{
		public getGameById ($id){
			return new Game($id, 0, 0, "test_name", array(0,1,2));
		}
	}
	class TestLocationRepository{
		public getLocationById ($id){
			return new Location($id, 0, "test_name", "test_street", "test_city", "test_state", "test_phone");
		}
	}
	class TestCategoryRepository{
		public getCategoryById ($id){
			return new Category($id, 0, "test_name");
		}
	}
	class TestUserRepository{
		public getUserById ($id){
			return new User($id, "test_login", 0, 0);
		}
	}

	class Repositories{
		public static function getGameRepository(){
			return new TestGameRepository();
		}
		public static function getLocationRepository(){
			return new TestLocationRepository();
		}
		public static function getCategoryRepository(){
			return new TestCategoryRepository();
		}
		public static function getUserRepository(){
			return new TestUserRepository();
		}
	}

?>
