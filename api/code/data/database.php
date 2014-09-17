<?php

	require_once ("initializeDb.php");

	class Database{
		public function initialize(){
			$con = connectAsAdmin();
			$con->query ("drop table game");
			$con->query ("create table game (id int not null auto_increment, locationId int not null, name varchar(100) not null, categoryIds int, primary key(id))");
			$con->query ("drop table location");
			$con->query ("create table location (id int not null auto_increment, name varchar(100) not null, street varchar(100), city varchar(20), state varchar(20), phone varchar(20), primary key(id))");
			$con->query ("drop table category");
			$con->query ("create table category (id int not null auto_increment, name varchar(100) not null, primary key(id))");
			$con->query ("drop table user");
			$con->query ("create table user (login varchar(30) not null auto_increment, type int not null, method int not null, primary key(login))");
			$con->query ("drop table city");
			$con->query ("create table city (id int not null auto_increment, name varchar(100) not null, state varchar(100) not null, country varchar(100), longitude float not null, latitude float not null, primary key(id))");
			$con->query ("drop table mtm_game_category");
			$con->query ("create table mtm_game_category (gameId int not null, categoryId int not null)");
		}
	}
?>
