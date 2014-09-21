insert into sport (name) values ('Basketball');
insert into city (name, state, country, longitude, latitude) values ('Grand Rapids', 'MI', 'United States', 42.9612, 85.6557);
insert into location (name, street, cityId, phone) values ('Harry\'s Hole in the Wall', '1234 Skidrow', last_insert_id(), '6165551234');
insert into game (locationId, name, sportId) values (last_insert_id(), 'Rumble in the Rapids', 1);