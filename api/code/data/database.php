<?php

	class Database{
		public function initialize(){
			$con = connectAsAdmin();
			$con->query ("drop table game");
			$con->query ("drop table location");
			$con->query ("drop table category");
			$con->query ("drop table user");
			$con->query ("drop table manyToOne_game_location");
		}
	}
?>
