<?php

	class TestGameRepository{
		public function getGameById ($id){
			return new Game($id, 0, "test_name", array(0,1,2));
		}
	}
	class TestLocationRepository{
		public function getLocationById ($id){
			return new Location($id, "test_name", "test_street", "test_city", "test_state", "test_phone");
		}
	}
	class TestCategoryRepository{
		public function getCategoryById ($id){
			return new Category($id, "test_name");
		}
	}
	class TestUserRepository{
		public function getUserById ($id){
			return new User($id, 0, 0);
		}
	}
	class TestCityRepository{
		public function getCityById ($id){
			return new City($id, "test_name", "test_state", "test_country", 0, 0);
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
		public static function getCityRepository(){
			return new TestCityRepository();
		}
	}

?>
