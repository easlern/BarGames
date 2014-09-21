<?php

	require_once ("initializeDb.php");

	class Database{
		public function initialize(){
			$con = connectAsAdmin();
			$con->query ("drop table if exists game");
			$con->query ("create table game (id int not null auto_increment, locationId int not null, name varchar(100) not null, sportId int not null, primary key(id))");
			$con->query ("drop table if exists sport");
			$con->query ("create table sport (id int not null auto_increment, name varchar(32), primary key(id))");
			$con->query ("drop table if exists team");
			$con->query ("create table team (id int not null auto_increment, name varchar(100), primary key(id))");
			$con->query ("drop table if exists location");
			$con->query ("create table location (id int not null auto_increment, name varchar(100) not null, street varchar(100), cityId int, phone varchar(20), primary key(id))");
			$con->query ("drop table if exists locationType");
			$con->query ("create table locationType (id int not null auto_increment, name varchar(32), primary key(id))");
			$con->query ("drop table if exists tag");
			$con->query ("create table tag (id int not null auto_increment, name varchar(100) not null, primary key(id))");
			$con->query ("drop table if exists user");
			$con->query ("create table user (email varchar(100) not null, type int not null, method int not null, passHash varchar(256) not null, nameFirst varchar(256) not null, nameLast varchar(256) not null, securityLevelId int not null, primary key(email))");
			$con->query ("drop table if exists securityLevel");
			$con->query ("create table securityLevel (id int not null auto_increment, name varchar(32) not null, primary key(id))");
			$con->query ("drop table if exists city");
			$con->query ("create table city (id int not null auto_increment, name varchar(100) not null, state varchar(100) not null, country varchar(100), longitude float not null, latitude float not null, primary key(id))");
			$con->query ("drop table if exists setting");
			$con->query ("create table setting (id int not null auto_increment, name varchar(32), defaultValue varchar(100), primary key(id))");
			$con->query ("drop table if exists userSetting");
			$con->query ("create table userSetting (userId int not null, settingId int not null, value varchar(0) not null)");
			$con->query ("alter table game add constraint fk_game_location foreign key (locationId) references location(id)");
			$con->query ("alter table user add constraint fk_user_securityLevel foreign key (securityLevelId) references securityLevel(id)");
			$con->query ("alter table userSetting add constraint fk_userSetting_setting foreign key (settingId) references setting(id)");
			$con->query ("drop table if exists mtm_game_tag");
			$con->query ("create table mtm_game_tag (gameId int not null, tagId int not null)");
			$con->query ("drop table if exists mtm_game_team");
			$con->query ("create table mtm_game_team (gameId int not null, teamId int not null)");
			$con->query ("alter table game add constraint fk_game_sport foreign key (sportId) references sport(id)");
			$con->query ("drop table if exists mtm_location_locationType");
			$con->query ("create table mtm_location_locationType (locationId int not null, locationTypeId int not null)");
			$con->query ("drop table if exists mtm_location_sport");
			$con->query ("create table mtm_location_sport (locationId int not null, sportId int not null)");
			$con->query ("alter table location add constraint fk_location_city foreign key (cityId) references city(id)");
		}
	}
?>
