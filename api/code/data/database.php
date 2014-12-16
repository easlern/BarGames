<?php

	require_once ("initializeDb.php");

	class Database{
		public function initialize(){
			$con = connectAsAdmin();
			$con->query ("drop table if exists Account");
			$con->query ("create table Account (ID int not null auto_increment, Password varchar(1000) not null, Username varchar(1000) not null, AccountPicturePath varchar(2000) not null, AccountTypeID int not null, IsDisabled boolean not null, LastLoginDate datetime, CREATEDATE datetime not null, LASTMODIFIEDDATE datetime, primary key(ID))");
			$con->query ("drop table if exists User");
			$con->query ("create table User (ID int not null auto_increment, AccountID int, FirstName varchar(200) not null, LastName varchar(200) not null, Email varchar(2000) not null, CityID int not null, MiddleName varchar(200), DisplayName varchar(1000), Description varchar(2000), BirthDate datetime, CREATEDATE datetime not null, LASTMODIFIEDDATE datetime, primary key(ID))");
			$con->query ("drop table if exists UserProfile");
			$con->query ("create table UserProfile (ID int not null auto_increment, UserID int not null, AboutMe varchar(5000), Interests varchar(5000), CREATEDATE datetime not null, LASTMODIFIEDDATE datetime, primary key(ID))");
			$con->query ("drop table if exists City");
			$con->query ("create table City (ID int not null auto_increment, Name varchar(500) not null, State varchar(50) not null, Country varchar(150) not null, Longitude float not null, Latitude float not null, CREATEDATE datetime not null, LASTMODIFIEDDATE datetime, primary key(ID))");
			$con->query ("alter table User add constraint fk_User_Account foreign key (AccountID) references Account(ID)");
			$con->query ("alter table User add constraint fk_User_City foreign key (CityID) references City(ID)");
			$con->query ("alter table UserProfile add constraint fk_UserProfile_User foreign key (UserID) references User(ID)");
		}
	}
?>
